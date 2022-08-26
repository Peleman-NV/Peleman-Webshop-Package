<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\editor\PWP_Editor_Project;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_I_Response_Component;

class PWP_PIE_Project extends PWP_Editor_Project implements PWP_I_Response, PWP_I_Response_Component
{
    private PWP_Product_PIE_Data $editorData;
    //TODO: fix PIE hardcoding with options.
    public function __construct(PWP_Product_PIE_Data $data, string $projectId)
    {
        $this->editorData = $data;
        parent::__construct(PWP_Product_PIE_Data::MY_EDITOR, $projectId);
    }

    public function get_project_editor_url(bool $skipUpload = false): string
    {
        $id = $this->get_project_id();
        
        $params = array();
        if ($skipUpload || !$this->editorData->uses_image_upload())
            $params['skip'] = 'true';

        $params = array_merge($params,$this->editorData->get_editor_params());
        $params['customerapikey'] = get_option('pie_api_key');

        $url = get_option('pie_domain') . "/editor/upload";
        $url .= "?projectid={$id}";

        $url .= '&' . http_build_query($params);
        return $url;
    }
}
