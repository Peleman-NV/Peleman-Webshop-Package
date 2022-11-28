<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\services\ImaxelService;

/**
 * Editor container object for Imaxel projects
 * @deprecated 1.0.0 PWP no longer supports IMAXEL
 */
class IMAXEL_Project extends Editor_Project
{
    private ImaxelService $service;

    private string $back_url;
    private string $lang = 'en';
    private string $add_to_cart_url;

    public function __construct(
        string $projectId,
        string $back_url,
        string $add_to_cart_url,
        string $lang = 'en'
    ) {
        $this->service = new ImaxelService();
        parent::__construct('IMAXEL', $projectId);
        $this->back_url = $back_url;
        $this->add_to_cart_url = $add_to_cart_url;
        $this->lang = $lang;
    }

    public function set_back_url(string $url): self
    {
        $this->back_url = $url;
        return $this;
    }

    public function set_add_to_cart_url(string $url): self
    {
        $this->add_to_cart_url = $url;
        return $this;
    }

    public function get_project_editor_url(bool $skipUpload = false): string
    {
        return $this->service->get_editor_url(
            $this->get_project_id(),
            $this->back_url,
            $this->lang,
            $this->add_to_cart_url
        );
    }
}
