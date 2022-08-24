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
        $url = get_option('pie_domain') . "/editor";
        $id = $this->get_project_id();
        $params = array();
        if ($this->editorData->get_max_images() > 0)
            $params['maximages'] = $this->editorData->get_max_images();

        if ($this->editorData->get_min_images() > 0)
            $params['minimages'] = $this->editorData->get_min_images();

        if ($this->editorData->get_uses_image_upload())
            $url .= '/upload';

        $url .= "?projectid={$id}";
        if ($params) {
            $url .= '&' . http_build_query($params);
        }

        return $url;
        // return "https://deveditor.peleman.com/?projectid={$id}";
    }
}
