<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use PWP\includes\services\entities\PWP_Project;

class PWP_Cleanup_Unordered_Projects extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        $hook = 'pwp_cleanup_projects';
        parent::__construct($hook, 'clean');

        if (!wp_next_scheduled($hook)) {
            wp_schedule_event(time(), 'daily', $hook);
        }
    }

    public function clean(): void
    {
        $days = (int)(get_option('pwp_project_cleanup_cutoff_days') ?: 15);
        $cutoffDate = wp_date('Y-m-d H:i:s', time() - ($days * 86400));

        $counter = 0;
        $projects = PWP_Project::get_all_unordered_projects();

        foreach ($projects as $project) {
            $lastUpdate = $project->get_updated()->getTimestamp();
            if ($lastUpdate <= $cutoffDate) {
                $counter++;
                $project->delete_files();
                $project->delete();
            }
        }

        if (0 < $counter) {
            error_log("deleted {$counter} projects from system.");
        }
        error_log("no projects deleted.");
    }
}
