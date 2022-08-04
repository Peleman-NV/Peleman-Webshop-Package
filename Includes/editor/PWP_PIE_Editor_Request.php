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

    public function create_new_project(PWP_PIE_Create_Project_Request_Data $requestData): array
    {
        $endpoint = $this->clientDomain . $this->newProjectEndpoint;
        return $this->do_GET_request($endpoint, $requestData->to_array());
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
    protected function do_GET_request(string $endpoint, array $requestData, bool $followRedirect = true, bool $secure = true): array
    {
        $endpoint .= '?' . http_build_query($requestData);

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
