<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\PWP_Abstract_Request;
use PWP\includes\services\ImaxelService;
use PWP\includes\utilities\response\PWP_I_Response;

class PWP_New_IMAXEL_Project_Request extends PWP_Abstract_Request
{
    private ImaxelService $service;
    private PWP_IMAXEL_Data $editorData;
    private string $backUrl;
    private string $addToCartUrl;

    public function __construct()
    {
        $this->service = new ImaxelService();
    }

    #region BUILDER METHODS
    public static function new(): self
    {
        return new PWP_New_IMAXEL_Project_Request();
    }

    public function set_secure(bool $secure = true): self
    {
        parent::set_secure($secure);
        return $this;
    }

    public function set_timeout(int $seconds): self
    {
        parent::set_timeout(($seconds));
        return $this;
    }

    public function initialize_from_imaxel_data(PWP_IMAXEL_Data $data): self
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

    public function make_request(): PWP_IMAXEL_Editor_Project
    {
        $jsonResponse = $this->service->create_project(
            $this->data->get_template_id(),
            $this->data->get_variation()
        );
        $response = json_decode($jsonResponse, true);

        $projectId = '';

        //TODO: parse response and create project

        return new PWP_IMAXEL_Editor_Project(
            $projectId,
            $this->backUrl,
            $this->addToCartUrl,
            'en'
        );
    }
}
