<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\I_Api_Authenticator;
use PWP\includes\services\entities\Project;
use WP_REST_Request;
use WP_REST_Response;

defined('ABSPATH') || die;

class FIND_PDF_Endpoint extends Abstract_FIND_Endpoint
{
    public function __construct(string $namespace, I_Api_Authenticator $authenticator, int $priority = 10)
    {
        parent::__construct(
            $namespace,
            '/pdf/(?P<id>\w+)',
            'pdf',
            $this->authenticator = $authenticator,
            $priority
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $projectId = (int)$request['id'];
        $project = Project::get_by_id($projectId);
        if(is_null($project)){
            exit();
        }
        //we get the current user with the nonce, but this code is still needed
        //to determine if the user is the owner of the PDF in question.
        // if (get_current_user_id() !== $project->get_user_id() || !current_user_can('edit_posts')) {
        //     return new WP_REST_Response(array(
        //         'message' => 'permission denied',
        //         'code' => 403,
        //     ), 403);
        // }

        // if (!$project) {
        //     return new WP_REST_Response(array(
        //         'message' => 'file not found',
        //         'code' => 404,
        //     ), 404);
        // }

        //in order to allow a PDF download, we bypass the WP_REST_Response requirement
        //instead, we do it the old fashioned way
        $filePath = PWP_UPLOAD_DIR . $project->get_path();
        $name = $project->get_file_name();
        ob_start();
        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($filePath));
        header("Content-disposition: inline; filename=\"{$name}\"");
        header('Pragma: public');
        ob_clean();
        flush();
        readfile($filePath);
        exit();
    }
}
