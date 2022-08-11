<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\exceptions\PWP_Invalid_Response_Exception;

#region EDITOR CONSTANTS
define('USE_DESIGN_MODE', 'usedesignmode');
define('USE_IMAGE_UPLOAD', 'useimageupload');
define('USE_BACKGROUNDS', 'usebackgrounds');
define('USE_DESIGNS', 'usedesigns');
define('USE_ELEMENTS', 'useelements');
define('USE_DOWNLOAD_PREVIEW', 'usedownloadpreview');
define('USE_OPEN_FILE', 'useopenfile');
define('USE_EXPORT', 'useexport');
define('SHOW_CROP_ZONE', 'useshowcropzone');
define('SHOW_SAFE_ZONE', 'useshowsafezone');
define('SHOW_STOCK_PHOTOS', 'useshowstockphotos');
define('USE_TEXT', 'usetext');
#endregion

class PWP_New_PIE_Project_Request
{
    private string $endpoint;
    private string $customerId;
    private string $apiKey;

    private bool $customizable;
    private string $userId;
    private string $templateId;
    private string $colorCode;
    private string $backgroundId;
    private string $language;
    private string $designId;

    private PWP_PIE_Data $pie_data;

    private array $editorInstructions;
    private string $projectName;
    private string $returnUrl;


    /**
     * Generate class to format and make a new project request to a Peleman Image Editor API
     *
     * @param string $endpoint 
     * @param string $customerID
     * @param string $apiKey
     */
    public function __construct(string $clientDomain,  string $customerID, string $apiKey)
    {
        $this->endpoint = $clientDomain . '/editor/api/createprojectAPI.php';
        $this->customerID = $customerID;
        $this->apiKey = $apiKey;

        $this->pie_data = null;

        $this->language = 'en';
        $this->editorInstructions = [];
        $this->projectName = '';
        $this->returnUrl = '';
    }

    public function initialize_from_product(\WC_Product $product): self
    {
        $this->pie_data = new PWP_PIE_Data($product->get_id());
        return $this;
    }

    public function initialize_from_pie_data(PWP_PIE_Data $data): self
    {
        $this->pie_data = $data;
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

    /**
     * Undocumented function
     *
     * @return PWP_PIE_Data|null 
     */
    public function data(): PWP_PIE_Data
    {
        return $this->pie_data;
    }

    public function is_customizable(): bool
    {
        //project is only customizable if it is set to customizable AND it has a template ID.
        return $this->customizable && $this->templateId;
    }

    /**
     * Make request to API to generate new PIE editor project
     *
     * @param integer $timeout default `30`. Time in seconds for the connection to time out. Longer durations mean the timeout will happen later.
     * @param boolean $secure default `true`. if true, will require both the client and API to use HTTPS. ONLY SET TO FALSE FOR DEBUGGING OR DEV
     * @return PWP_PIE_Editor_Project|null if successful, will return a new editor project object. If the API request cannot
     * be made due to missing data, or incorrect settings, will return null. In
     * @throws PWP_Invalid_Response_Exception if no response is received from the server
     */
    public function make_request(int $timeout = 30, bool $secure = true): PWP_PIE_Editor_Project
    {
        $url = $this->endpoint .= '?' . http_build_query($this->request_to_array());

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 1,
            // CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_ENCODING => "",
            CURLOPT_SSL_VERIFYPEER => $secure ? 1 : 0,
            CURLOPT_SSL_VERIFYHOST => $secure ? 2 : 0,
            CURLOPT_RETURNTRANSFER => 1,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        if (empty($response) || is_bool($response)) {
            throw new PWP_Invalid_Response_Exception('No valid response received. Try again later.');
            //TODO: handle invalid response
        }

        $response = json_decode($response, true, 512, 0);

        return new PWP_PIE_Editor_Project($response);
    }

    public function request_to_array(): array
    {
        $data = array(
            'userid' => $this->userId,
            'templateid' => $this->templateId,
            'designid' => $this->designId,
            'language' => $this->language,
            'returnurl' => $this->returnUrl,
        );

        if (!empty($this->editorInstructions)) $data['editorinstructions'] = $this->editorInstructions;
        if (!empty($this->backgroundId)) $data['backgroundId'] = $this->backgroundId;
        if (!empty($this->colorCode)) $data['colorcode'] = $this->colorCode;
        if (!empty($this->projectName)) $data['projectname'] = $this->projectName;

        return $data;
    }
}
