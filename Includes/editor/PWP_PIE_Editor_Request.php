<?php

declare(strict_types=1);

namespace PWP\includes\Editor;

use PWP\includes\editor\PWP_PIE_Create_Project_Request_Data;

class PWP_Pie_Editor_Request
{
    private string $clientDomain;
    private string $newProjectEndpoint;

    private int $timeout = 10;
    public function __construct(string $domainUrl)
    {
        $this->clientDomain = $domainUrl;
        $this->newProjectEndpoint = '/editor/api/createprojectAPI.php';
    }

    public function create_new_project(PWP_PIE_Create_Project_Request_Data $requestData)
    {
        $endpoint = $this->clientDomain . $this->newProjectEndpoint;
        $response = $this->do_GET_request($endpoint, $requestData->to_array());
        return $response['project_id'];
        //TODO: parse response data
        //FIXME: currently not receiving response data?
    }

    /**
     * Undocumented function
     *
     * @param string $endpoint target URL to make a request of. Generally an API endpoint.
     * @param array $requestData data of the request. method will convert array into a GET request string.
     * @param boolean $followRedirect wether the request has to follow redirects from the target URL. default true.
     * @param boolean $secure wether the request has to be done over HTTPS or not. Default `true`, only set to false in dev environments.
     * @return array response of the GET request.
     */
    protected function do_GET_request(string $endpoint, array $requestData, bool $followRedirect = true, bool $secure = false)
    {
        $endpoint .= '?' . http_build_query($requestData);
        error_log($endpoint);

        $curl = curl_init($endpoint);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            // CURLOPT_FAILONERROR => true,
            // CURLOPT_FOLLOWLOCATION => $followRedirect,
            // CURLOPT_MAXREDIRS => 2,
            // CURLOPT_HEADER => true,
            // CURLOPT_TIMEOUT => $this->timeout,
            // CURLOPT_CONNECTTIMEOUT => $this->timeout,
            // CURLOPT_ENCODING => "",
            // CURLOPT_SSL_VERIFYPEER => $secure ? 1 : 0,
            // CURLOPT_SSL_VERIFYHOST => $secure ? 2 : 0,
            CURLOPT_RETURNTRANSFER => 1,
        ));

        $response = curl_exec($curl);
        error_log("response: " . print_r($response, true));
        curl_close($curl);
        if (empty($response) || is_bool($response)) {
            return $response;
            //TODO: handle invalid response
        }

        return json_decode($response, true, 512, 0);
    }
}
