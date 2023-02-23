<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\I_Api_Authenticator;
use PWP\includes\exceptions\WP_Error_Exception;
use WP_REST_Response;

defined('ABSPATH') || die;

class FIND_Project_Thumbnail extends Abstract_FIND_Endpoint
{
    public function __construct(string $namespace, I_Api_Authenticator $authenticator, int $priority = 10)
    {
        parent::__construct(
            $namespace,
            '/thumb/(?P<projectId>[a-z0-9_\-]+)',
            'thumbnail',
            $this->authenticator = $authenticator,
            $priority
        );
    }

    public function do_action(\WP_REST_Request $request): WP_REST_Response
    {
        $projectId = $request['projectId'];
        $src = '';
        $url = $this->generate_thumbnail_request_url($projectId);
        error_log($url);

        try {
            $img = wp_remote_get($url);
            if (is_wp_error($img)) {
                throw new WP_Error_Exception($img);
            }
            $img = $img['body'];

            if (!$img)  return rest_ensure_response('');
            ob_start();
            header('Content-Type: image/jpeg');
            echo ($img);
            ob_flush();
            ob_end_clean();
        } catch (WP_Error_Exception $error) {
            error_Log((string)$error);
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
