<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\exceptions\PWP_Invalid_Response_Exception;
use PWP\includes\PWP_Abstract_Request;

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

class PWP_New_PIE_Project_Request extends PWP_Abstract_Request
{
    #region CLASS VARIABLES
    private string $endpoint;
    private string $customerId;
    private string $apiKey;

    private ?PWP_Product_PIE_Data $editorData;

    private int $userId;
    private string $language;
    private array $editorInstructions;
    private string $projectName;
    private string $returnUrl;

    private string $formatId;
    #endregion

    public function __construct(string $clientDomain,  string $customerId, string $apiKey)
    {
        $this->endpoint = $clientDomain . '/editor/api/createprojectAPI.php';
        $this->customerId = $customerId;
        $this->apiKey = $apiKey;

        $this->editorData = null;

        $this->userId = 0;
        $this->language = 'en';
        $this->editorInstructions = [];
        $this->projectName = '';
        $this->returnUrl = '';

        $this->formatId = '';
    }

    #region BUILDER METHODS

    public static function new(string $clientDomain, string $customerId, string $apiKey): self
    {
        return new PWP_New_PIE_Project_Request($clientDomain, $customerId, $apiKey);
    }

    public function set_secure(bool $secure = true): self
    {
        parent::set_secure($secure);
        return $this;
    }

    public function set_timeout(int $seconds): self
    {
        parent::set_timeout(($seconds));
        return $this;
    }

    public function initialize_from_product(\WC_Product $product): self
    {
        $this->editorData = new PWP_Product_Meta_Data($product);
        return $this;
    }

    public function initialize_from_pie_data(PWP_Product_PIE_Data $data): self
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

    public function data(): PWP_Product_PIE_Data
    {
        return $this->editorData;
    }

    public function is_customizable(): bool
    {
        //project is only customizable if it is set to customizable AND it has a template Id.
        return $this->customizable && $this->templateId;
    }

    public function make_request(): PWP_PIE_Project
    {
        $url = $this->endpoint .= '?' . http_build_query($this->generate_request_array());

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $url,
            CURLOPT_TIMEOUT         => $this->timeout,
            CURLOPT_CONNECTTIMEOUT  => $this->timeout,
            CURLOPT_SSL_VERIFYPEER  => $this->secure ? 1 : 0,
            CURLOPT_SSL_VERIFYHOST  => $this->secure ? 2 : 0,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_HTTPHEADER      => $this->generate_request_header(),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        error_log('editor response: ' . print_r($response, true));
        if (empty($response) || is_bool($response)) {
            throw new PWP_Invalid_Response_Exception('No valid response received. Likely an authentication issue. Try again later.');
        }

        //use this code when the api returns a json array, which right now it does not do
        // $response = json_decode($response, true, 512, 0);
        // return new PWP_PIE_Project($response['project_id']);

        return new PWP_PIE_Project($this->editorData, $response);
    }

    protected function generate_request_array(): array
    {
        $request = array(
            'customerid'            => $this->customerId,
            'customerapikey'        => $this->apiKey,
            'userid'                => $this->userId,
            'language'              => $this->language,
            'templateid'            => $this->editorData->get_template_id(),
            'designid'              => $this->editorData->get_design_id(),
            'backgroundId'          => $this->editorData->get_background_id(),
            'colorcode'             => $this->editorData->get_color_code(),
            'formatid'              => $this->editorData->get_format_id(),
            'editorinstructions'    => array_merge($this->editorData->get_editor_instructions(), $this->editorInstructions),
            'projectname'           => $this->projectName,
            'returnurl'             => $this->returnUrl,
        );

        $request = array_filter($request);
        return $request;
    }

    protected function generate_request_header(): array
    {
        $referer = get_site_url();
        $header = array(
            // "PIEAPIKEY : {$this->apiKey}",
        );

        error_log("header: " . print_r($header, true));
        return $header;
    }
}
