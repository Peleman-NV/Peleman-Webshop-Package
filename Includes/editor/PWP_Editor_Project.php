<?php

declare(strict_types=1);

namespace pwp\includes\editor;

use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_I_Response_Component;

abstract class PWP_Editor_Project implements PWP_I_Response, PWP_I_Response_Component
{
    private string $editorId;
    private string $projectId;

    public function __construct(string $editorId, string $projectId)
    {
        $this->editorId = $editorId;
        $this->projectId = $projectId;
    }

    public function get_project_id(): string
    {
        return $this->projectId;
    }

    public function get_editor_id(): string
    {
        return $this->editorId;
    }

    public abstract function get_project_editor_url(): string;

    public function to_array(): array
    {
        return array(
            '_editor_id'    => $this->get_editor_id(),
            '_project_id'   => $this->get_project_id(),
            '_project_url'  => $this->get_project_editor_url(),
        );
    }
}
