<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\editor\PWP_Editor_Project;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_I_Response_Component;

class PWP_PIE_Editor_Project extends PWP_Editor_Project implements PWP_I_Response, PWP_I_Response_Component
{
    //TODO: fix PIE hardcoding with options.
    public function __construct(string $projectId)
    {
        parent::__construct('PIE', $projectId);
    }

    public function get_project_editor_url(): string
    {
        $id = $this->get_project_id();
        return "https://deveditor.peleman.com/?projectid={$id}";
    }

    public function to_array(): array
    {
        return array(
            'editor' => $this->get_editor_id(),
            'pie_project_id' => $this->get_project_id(),
            'pie_project_url' => $this->get_project_editor_url(),

        );
    }
}
