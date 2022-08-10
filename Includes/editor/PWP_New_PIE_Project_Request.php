<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\utilities\response\PWP_I_Response;

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

    private string $userId;
    private string $templateId;
    private string $colorCode;
    private string $backgroundId;
    private string $language;
    private string $designId;
    /**
     * @var string[]
     */
    private array $editorInstructions;
    private string $projectName;
    private string $returnUrl;

    public function __construct()
    {
    }

    public function make_request(int $timeout = 30, bool $secure = true): PWP_PIE_Editor_Project
    {
        $url = $this->endpoint .= '?' . http_build_query($this->request_to_array());

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_HEADER => false,
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
