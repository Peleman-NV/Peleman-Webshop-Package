<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\editor\PWP_Editor_Project;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_I_Response_Component;

class PWP_PIE_Editor_Project extends PWP_Editor_Project implements PWP_I_Response, PWP_I_Response_Component
{
    private PWP_PIE_DATA $editorData;
    //TODO: fix PIE hardcoding with options.
    public function __construct(PWP_PIE_Data $data, string $projectId)
    {
        $this->editorData = $data;
        parent::__construct(PWP_PIE_Data::MY_EDITOR, $projectId);
    }

    public function get_project_editor_url(): string
    {
        $id = $this->get_project_id();
        $params = $this->editorData->get_editor_params();
        $params['customerapikey'] = get_option('pie_api_key');

        $url = get_option('pie_domain') . "/editor/upload";
        $url .= "?projectid={$id}";

        //add parameter to force the editor to skip the upload screen
        if (!$params) {
            $params = ['skip' => true];
        }
        $url .= '&' . http_build_query($params);

        return $url;
    }
}
