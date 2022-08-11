<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\exceptions\PWP_Invalid_Response_Exception;
use PWP\includes\PWP_Abstract_Request;

#region PIE EDITOR CONSTANTS
define('PIE_USE_DESIGN_MODE', 'usedesignmode');
define('PIE_USE_IMAGE_UPLOAD', 'useimageupload');
define('PIE_USE_BACKGROUNDS', 'usebackgrounds');
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

    private ?PWP_PIE_Data $editorData;

    private int $userId;
    private string $language;
    private array $editorInstructions;
    private string $projectName;
    private string $returnUrl;
    #endregion


    /**
     * Generate class to format and make a new project request to a Peleman Image Editor API
     *
     * @param string $endpoint 
     * @param string $customerId
     * @param string $apiKey
     */
    public function __construct(string $clientDomain,  string $customerId, string $apiKey)
    {
        $this->endpoint = $clientDomain . '/editor/api/createprojectAPI.php';
        $this->customerId = $customerId;
        $this->apiKey = $apiKey;

        $this->editorData = null;

        $this->language = 'en';
        $this->editorInstructions = [];
        $this->projectName = '';
        $this->returnUrl = '';
    }

    #region BUILDER METHODS
    /**
     * static wrapper for the class constructor. Returns an instance of itself for easy builder method chaining.
     *
     * @param string $clientDomain
     * @param string $customerId
     * @param string $apiKey
     * @return self
     */
    public static function new(string $clientDomain, string $customerId, string $apiKey): self
    {
        return new PWP_New_PIE_Project_Request($clientDomain, $customerId, $apiKey);
    }

    /**
     * sets wether a request made with this class should be secure or not,
     * meaning the connection has to be made over HTTPS.
     * Set to true by default, only set to false in dev environments or for debugging.
     *
     * @param boolean $secure
     * @return self
     */
    public function set_secure(bool $secure = true): self
    {
        parent::set_secure($secure);
        return $this;
    }

    /**
     * timeout period in seconds for the request
     *
     * @param integer $seconds
     * @return self
     */
    public function set_timeout(int $seconds): self
    {
        parent::set_timeout(($seconds));
        return $this;
    }

    /**
     * Initialize project settings from a WC_Product object.
     *
     * @param \WC_Product $product
     * @return self
     */
    public function initialize_from_product(\WC_Product $product): self
    {
        $this->editorData = new PWP_PIE_Data($product->get_id());
        return $this;
    }

    /**
     * Inject a PWP_PIE_Data object for the project settings
     *
     * @param PWP_PIE_Data $data
     * @return self
     */
    public function initialize_from_pie_data(PWP_PIE_Data $data): self
    {
        $this->editorData = $data;
        return $this;
    }

    /**
     * Set current user ID. this will be the customer of the webshop who is making a project, and is thus the project owner.
     *
     * @param integer $userId
     * @return self
     */
    public function set_user_id(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * set Return URL to which the user will be redirected after saving their project.
     *
     * @param string $returnURL
     * @return self
     */
    public function set_return_url(string $returnURL): self
    {
        $this->returnUrl = $returnURL;
        return $this;
    }

    /**
     * set PIE Editor instructions. refer to API documentation.
     *
     * @param string ...$args editor instructions. instructions are defined as constants beginning with PIE_
     * @return self
     */
    public function set_editor_instructions(string ...$args): self
    {
        $this->editorInstructions = $args;
        return $this;
    }

    /**
     * Set two letter language code for PIE editor to use in displaying the editor
     *
     * @param string $lang
     * @return self
     */
    public function set_language(string $lang): self
    {
        $this->language = $lang;
        return $this;
    }

    /**
     * set name of the project to be generated. This is purely for display/clarity
     *
     * @param string $name
     * @return self
     */
    public function set_project_name(string $name): self
    {
        $this->projectName = $name;
        return $this;
    }
    #endregion
    /**
     * editor data of the parent product
     *
     * @return PWP_PIE_Data
     */
    public function data(): PWP_PIE_Data
    {
        return $this->editorData;
    }

    /**
     * wether the project is customizable.
     * a project is customizable if
     * 1) the project is flagged as customizable on the product page
     * 2) the project has a non-empty template ID
     *
     * @return boolean
     */
    public function is_customizable(): bool
    {
        //project is only customizable if it is set to customizable AND it has a template Id.
        return $this->customizable && $this->templateId;
    }

    /**
     * Make request to API to generate new PIE editor project
     *
     * @return PWP_PIE_Editor_Project|null if successful, will return a new editor project object. If the API request cannot
     * be made due to missing data, or incorrect settings, will return null.
     * @throws PWP_Invalid_Response_Exception if no response is received from the server
     */
    public function make_request(): PWP_PIE_Editor_Project
    {
        $url = $this->endpoint .= '?' . http_build_query($this->generate_request_array());

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $url,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 1,
            CURLOPT_HTTPHEADER      => $this->generate_request_header(),
            CURLOPT_TIMEOUT         => $this->timeout,
            CURLOPT_CONNECTTIMEOUT  => $this->timeout,
            CURLOPT_SSL_VERIFYPEER  => $this->secure ? 1 : 0,
            CURLOPT_SSL_VERIFYHOST  => $this->secure ? 2 : 0,
            CURLOPT_RETURNTRANSFER  => 1,
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (empty($response) || is_bool($response)) {
            throw new PWP_Invalid_Response_Exception('No valid response received. Likely an authentication issue. Try again later.');
        }

        $response = json_decode($response, true, 512, 0);

        return new PWP_PIE_Editor_Project($response['project_id']);
    }

    /**
     * convert parameters into associative array for making a request
     *
     * @return array request array. can be converted into a GET string or a JSON.
     */
    protected function generate_request_array(): array
    {
        $request = array(
            'customerid'            => $this->customerId,
            'customerapikey'        => $this->apiKey,
            'userid'                => $this->userId,
            'language'              => $this->language,
            'templateid'            => $this->editorData->get_template_id(),
            'designid'              => $this->editorData->get_template_id(),
            'backgroundId'          => $this->editorData->get_background_id(),
            'colorcode'             => $this->editorData->get_color_code(),
            'editorinstructions'    => $this->editorInstructions,
            'projectname'           => $this->projectName,
            'returnurl'             => $this->returnUrl,

        );

        $request = array_filter($request);
        return $request;
    }

    /**
     * generate header for making a request
     *
     * @return array
     */
    protected function generate_request_header(): array
    {
        $referer = get_site_url();
        $header = array();

        // $header[] = "PieAPIKey : {$this->apiKey}";
        $header[] = "referer : {$referer}";

        return $header;
    }
}
