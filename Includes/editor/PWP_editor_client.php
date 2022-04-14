<?php

declare(strict_types=1);

namespace PWP\includes\editor;

class PWP_editor_client
{
    private string $url;
    public function __construct(string $url)
    {
        if ($url === '') {
            $this->url = site_url();
        }
        $this->url = $url;
    }
    public function get_new_project_url(string $templateId, string $variantId, string $languageCode): string
    {
        if ($languageCode == '') {
            $languageCode = 'en';
        }

        return sanitize_url(sprintf(
            'https://%s/demo/index.php?id=new&templateFile=%s.json&variantId=%s&language=%s',
            $this->url,
            $templateId,
            $variantId,
            $languageCode
        ));
    }

    public function get_existing_project_url(string $projectId, string $variantId, string $languageCode): string
    {
        if ($languageCode == '') {
            $languageCode = 'en';
        }

        return sanitize_url(sprintf(
            'https://%s/index.php?projectId=%s&variantId=%s',
            $this->url,
            $projectId,
            $variantId
        ));
    }
}
