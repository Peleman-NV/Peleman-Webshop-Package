<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\Abstract_Request;
use PWP\includes\services\ImaxelService;

/**
 * @deprecated 1.0.0 PWP no longer supports IMAXEL
 */
class New_IMAXEL_Project_Request extends Abstract_Request
{
    private ImaxelService $service;
    private Product_IMAXEL_Data $editorData;
    private string $backUrl;
    private string $addToCartUrl;

    public function __construct()
    {
        $this->service = new ImaxelService();
    }

    #region BUILDER METHODS
    public static function new(): self
    {
        return new New_IMAXEL_Project_Request();
    }
    
    public function initialize_from_imaxel_data(Product_IMAXEL_Data $data): self
    {
        $this->editorData = $data;
        return $this;
    }

    public function set_user_id(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function set_back_url(string $url): self
    {
        $this->backUrl = $url;
        return $this;
    }
    public function set_add_to_cart_url(string $url): self
    {
        $this->addToCartUrl = $url;
        return $this;
    }
    #region

    public function make_request(): IMAXEL_Project
    {
        $response = $this->service->create_project(
            $this->editorData->get_template_id(),
            $this->editorData->get_variant_id()
        );
        $response_body = json_decode($response['body'], true);
        $response_code = $response['response']['code'];
        // error_log(print_r($response_body, true));

        $projectId = $response_body['id'];

        //TODO: parse response and create project

        return new IMAXEL_Project(
            $projectId,
            $this->backUrl,
            $this->addToCartUrl,
            //TODO: fix hard coded lang parameter
            'en'
        );
    }
}
