<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\I_Api_Authenticator;
use WP_REST_Response;

defined('ABSPATH') || die;

class FIND_Project_Thumbnail extends Abstract_FIND_Endpoint
{
    public function __construct(string $namespace, I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            '/thumb/(?P<projectId>[a-z0-9_\-]+)',
            'thumbnail',
            $this->authenticator = $authenticator
        );
    }

    public function do_action(\WP_REST_Request $request): WP_REST_Response
    {
        $projectId = $request['projectId'];
        $src = '';
        $url = $this->generate_thumbnail_request_url($projectId);

        try {
            $img = wp_remote_get($url);
            //TODO: error handling in case of invalid response.
            $img = $img['body'];

            if (!$img || $img === false)  return rest_ensure_response('');
            $src = $img;
            ob_start();
            header('Content-Type: image/jpeg');
            ob_clean();
            flush();
            echo $img;
            exit;
        } catch (\Throwable $error) {
            error_log((string)$error);
        } finally {
            exit;
        }
    }

    private function generate_thumbnail_request_url(string $projectId): string
    {
        $domain = get_option('pie_domain');

        $query = array(
            'projectid' => $projectId,
            'customerapikey' => get_option('pie_api_key'),
        );

        return $domain . "/editor/api/getprojectthumbnailAPI.php" . '?' . http_build_query($query);
    }
}
