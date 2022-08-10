<?php

declare(strict_types=1);

namespace pwp\includes\editor;

abstract class PWP_Editor_Project
{
    private string $editorID;

    public function __construct($editorId)
    {
        $this->editorID = $editorId;
    }

    public abstract function get_project_id(): string;
    public abstract function get_project_editor_url(): string;

    public function get_editor_id(): string
    {
        return $this->editorID;
    }
}
