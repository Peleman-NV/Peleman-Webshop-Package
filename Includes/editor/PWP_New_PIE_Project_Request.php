<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use JsonSerializable;
use PWP\includes\editor\PWP_PIE_Data;

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

class PWP_New_PIE_Project_Request implements JsonSerializable
{

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

    public function __construct(string $userId, PWP_PIE_Data $data, string $returnUrl)
    {
        $this->userId = $userId;
        $this->templateId = $data->get_template_id();
        $this->colorCode = '';
        $this->backgroundId = '';
        $this->language = 'en';
        $this->designId = '';
        $this->editorInstructions = array();
        $this->projectName = '';
        $this->returnUrl = $returnUrl;
    }

    #region Builder Methods
    public function set_color_code(string $color): self
    {
        $this->colorCode = $color;
        return $this;
    }

    public function set_background_id(string $id): self
    {
        $this->backgroundId = $id;
        return $this;
    }

    public function set_language(string $lang): self
    {
        $this->language = $lang;
        return $this;
    }

    public function set_design_id(string $id): self
    {
        $this->designId = $id;
        return $this;
    }
    public function set_editor_instructions(string ...$args): self
    {
        $this->editorInstructions = $args;
        return $this;
    }

    public function set_project_name(string $name): self
    {
        $this->projectName = $name;
        return $this;
    }

    public function set_return_url(string $url): self
    {
        $this->returnUrl = $url;
        return $this;
    }
    #endregion

    public function to_array(): array
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

    public function jsonSerialize(): mixed
    {
        return $this->to_array();
    }
}
