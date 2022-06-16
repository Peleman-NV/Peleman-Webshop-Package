<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\utilities\clients\PWP_New_PIE_Project_Request;

class PWP_Editor_Client
{
    private string $clientDomain;
    private string $newProjectEndpoint;

    private int $timeout = 10;
    public function __construct(string $domainUrl)
    {
        $this->clientDomain = $domainUrl;
        $this->newProjectEndpoint = '/editor/api/createprojectAPI.php';
    }

    public function create_new_project(PWP_New_PIE_Project_Request $request): array
    {
        $endpoint = $this->clientDomain . $this->newProjectEndpoint;
        $response = $this->do_get($endpoint, $request->to_array());
        //TODO: parse response data

        var_dump($response);
        return array();
    }

    public function do_get(string $endpoint, array $request, bool $followRedirect = false, bool $secure = true): array
    {
        $endpoint .= '?' . http_build_query($request);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_FOLLOWLOCATION => $followRedirect,
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_ENCODING => "",
            CURLOPT_SSL_VERIFYPEER => $secure ? 1 : 0,
            CURLOPT_SSL_VERIFYHOST => $secure ? 2 : 0,
            CURLOPT_RETURNTRANSFER => 1,

        ));

        $response = curl_exec($curl);
        curl_close($curl);
        if (empty($response) || is_bool($response)) {
            //TODO: handle invalid response
        }

        return json_decode($response, true, 512, 0);
    }
}
