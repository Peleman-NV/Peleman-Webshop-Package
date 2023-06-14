<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Filter_Hookable;
use PWP\includes\services\entities\Project;

class Get_PDF_File_From_Project extends Abstract_Filter_Hookable
{
    public function __construct(int $priority = 1)
    {
        parent::__construct('pwp_get_project_pdf_data', 'get_pdf_data', $priority);
    }

    public function get_pdf_data(?array $data, int $projectId = 0): ?array
    {
        if (!$projectId) {
            return $data;
        }
        $project = Project::get_by_id($projectId);
        if (!$project) {
            return $data;
        }

        $path = PWP_UPLOAD_DIR . $project->get_path();
        if (!file_exists($path)) {
            return $data;
        }
        $name = $project->get_file_name();

        $data = array(
            'path' => $path,
            'name' => $name,
        );

        return $data;
    }
}
