<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\services\entities\PWP_Project;
use WP_REST_Request;
use WP_REST_Response;

defined('ABSPATH') || die;

class PWP_FIND_PDF_Endpoint extends PWP_Abstract_FIND_Endpoint
{
    public function __construct(string $namespace, PWP_I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            '/pdf/(?P<id>\w+)',
            'pdf',
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $projectId = (int)$request['id'];
        $project = PWP_Project::get_by_id($projectId);

        if (!$project) {
            return new WP_REST_Response(array(
                'message' => 'File not found!',
                'code' => 404
            ), 404);
        }

        //in order to allow a PDF download, we bypass the WP_REST_Response requirement
        //instead, we do it the old fashioned way
        $filePath = PWP_UPLOAD_DIR . $project->get_path();

        $name = $project->get_file_name();
        ob_start();
        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($filePath));
        header("Content-disposition: attachment; filename=\"{$name}\"");
        header('Pragma: public');
        ob_clean();
        flush();
        readfile($filePath);
        exit();
    }
}
