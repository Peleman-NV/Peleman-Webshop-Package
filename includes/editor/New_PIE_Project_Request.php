<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\exceptions\Invalid_Response_Exception;
use PWP\includes\Abstract_Request;

#region PIE EDITOR CONSTANTS
/**
 * open editor in design mode for editors
 */
define('PIE_USE_DESIGN_MODE', 'usedesignmode');
/**
 * allow user to upload images
 */
define('PIE_USE_IMAGE_UPLOAD', 'useimageupload');
/**
 * use 
 */
define('PIE_USE_BACKGROUNDS', 'usebackgrounds');
/**
 * allow users to use custom designs
 * set to false
 */
define('PIE_USE_DESIGNS', 'usedesigns');
define('PIE_USE_ELEMENTS', 'useelements');
define('PIE_USE_DOWNLOAD_PREVIEW', 'usedownloadpreview');
define('PIE_USE_OPEN_FILE', 'useopenfile');
define('PIE_USE_EXPORT', 'useexport');
define('PIE_SHOW_CROP_ZONE', 'useshowcropzone');
define('PIE_SHOW_SAFE_ZONE', 'useshowsafezone');
define('PIE_SHOW_STOCK_PHOTOS', 'useshowstockphotos');
define('PIE_USE_TEXT', 'usetext');
#endregion

class New_PIE_Project_Request extends Abstract_PIE_Request
{
    #region CLASS VARIABLES
    private ?Product_PIE_Data $editorData;

    private int $userId;
    private string $language;
    private array $editorInstructions;
    private string $projectName;
    private string $returnUrl;

    private string $formatId;
    #endregion

    public function __construct(string $clientDomain,  string $customerId, string $apiKey)
    {
        $endpoint = '/editor/api/createprojectAPI.php';
        parent::__construct($clientDomain, $endpoint, $apiKey, $customerId);

        $this->editorData = null;

        $this->userId = 0;
        $this->language = substr(get_locale(), 0, 2) ?: 'en';
        $this->editorInstructions = [];
        $this->projectName = '';
        $this->returnUrl = '';

        $this->formatId = '';

        $this->set_GET();
    }

    #region BUILDER METHODS

    public static function new(string $clientDomain, string $customerId, string $apiKey): self
    {
        return new New_PIE_Project_Request($clientDomain, $customerId, $apiKey);
    }

    public function initialize_from_product(\WC_Product $product): self
    {
        $this->editorData = new Product_Meta_Data($product);
        return $this;
    }

    public function initialize_from_pie_data(Product_PIE_Data $data): self
    {
        $this->editorData = $data;
        return $this;
    }

    public function set_user_id(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function set_return_url(string $returnURL): self
    {
        $this->returnUrl = $returnURL;
        return $this;
    }

    public function set_editor_instructions(string ...$args): self
    {
        $this->editorInstructions = $args;
        return $this;
    }

    public function set_language(string $lang): self
    {
        $this->language = $lang;
        return $this;
    }

    public function set_project_name(string $name): self
    {
        $this->projectName = $name;
        return $this;
    }

    public function set_format_id(string $id): self
    {
        $this->formatId = $id;
        return $this;
    }

    #endregion

    public function data(): Product_PIE_Data
    {
        return $this->editorData;
    }

    public function is_customizable(): bool
    {
        //project is only customizable if it is set to customizable AND it has a template Id.
        return $this->customizable && $this->templateId;
    }

    public function make_request(): PIE_Project
    {
        $response = wp_remote_request($this->get_endpoint_url(), array(
            'method'    => $this->get_method(),
            'timeout'   => $this->timeout,
            'header'    => $this->generate_request_header(),
            'body'      => $this->generate_request_body(),
        ));

        //TODO: use improved request feedback to bolster error response system
        if (is_wp_error($response)) {
            throw new Invalid_Response_Exception(__('Could not connect to Peleman Image Editor. Please try again later.', PWP_TEXT_DOMAIN));
        }

        $responseBody = sanitize_key($response['body']);
        $responseArr = $response['response'];
        error_log('editor response: ' . print_r($responseBody, true));
        if (empty($responseBody) || is_bool($responseBody)) {
            throw new Invalid_Response_Exception(__('No valid response received. Likely an authentication issue. Please check the validity of your Peleman Editor credentials.', PWP_TEXT_DOMAIN));
        }

        return new PIE_Project($this->editorData, $responseBody);
    }

    protected function generate_request_body(): array
    {
        $request = array(
            'customerid'            => $this->get_customer_id(),
            'customerapikey'        => $this->get_api_key(),
            'userid'                => $this->userId,
            'language'              => $this->language,
            'templateid'            => $this->editorData->get_template_id(),
            'designid'              => $this->editorData->get_design_id(),
            'backgroundid'          => $this->editorData->get_background_id(),
            'colorcode'             => $this->editorData->get_color_code(),
            'formatid'              => $this->editorData->get_format_id(),
            'editorinstructions'    => array_merge($this->editorData->get_editor_instructions(), $this->editorInstructions),
            'projectname'           => $this->projectName,
            'returnurl'             => $this->returnUrl,
        );
        return $request;
    }
}
