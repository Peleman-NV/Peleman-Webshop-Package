<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\editor\Editor_Project;
use PWP\includes\utilities\response\I_Response;

class PIE_Project extends Editor_Project
{
    private Product_PIE_Data $editorData;
    //TODO: fix PIE hardcoding with options.
    public function __construct(Product_PIE_Data $data, string $projectId)
    {
        $this->editorData = $data;
        parent::__construct(Product_PIE_Data::MY_EDITOR, $projectId);
    }

    public function get_project_editor_url(bool $skipUpload = false): string
    {
        $id = $this->get_project_id();

        $params = array();
        if ($skipUpload || !$this->editorData->uses_image_upload())
            $params['skip'] = 'true';

        $params = array_merge($params, $this->editorData->get_editor_params());
        $params['customerapikey'] = get_option('pie_api_key');
        $params['lang'] = $this->get_editor_lang();

        $url = apply_filters('pwp_generate_pie_project_url', '', $id, $params);

        return $url;
    }

    private function get_editor_lang(): string
    {
        if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE) {
            return ICL_LANGUAGE_CODE;
        }
        return explode("_", get_locale())[0];
    }
}
