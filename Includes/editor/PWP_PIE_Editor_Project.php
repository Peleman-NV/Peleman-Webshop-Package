<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\editor\PWP_Editor_Project;

class PWP_PIE_Editor_Project extends PWP_Editor_Project
{
    private string $projectID;
    public function __construct(string $projectId)
    {
        $this->projectID = $projectId;
        parent::__construct('PIE');
    }

    public function get_project_id(): string
    {
        return $this->projectID;
    }

    public function get_project_editor_url(): string
    {
        $id = $this->projectID;
        return "https://deveditor.peleman.com/?projectid={$id}";
    }
}
