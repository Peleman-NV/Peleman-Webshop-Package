<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;

class PWP_Add_Cron_Schedules extends PWP_Abstract_Filter_Hookable
{
    public const DAY = 86400;
    
    public function __construct()
    {
        parent::__construct('cron_schedules', 'add_schedules');
    }

    public function add_schedules(array $schedules)
    {
        $schedules['twiceweekly'] = array(
            'interval' => (int)(3.5 * self::DAY),
            'display' => __('Twice a week'),
        );
        $schedules['weekly'] = array(
            'interval' => 7 * self::DAY,
            'display' => __('Once a week'),
        );
        $schedules['twicemonthly'] = array(
            'interval' => 15 * self::DAY,
            'display' => __('twice every month (15 days)'),
        );
        $schedules['monthly'] = array(
            'interval' => 30 * self::DAY,
            'display' => __('every month (30 days)'),
        );
    }
}
