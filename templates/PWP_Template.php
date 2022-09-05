<?php

declare(strict_types=1);

namespace PWP\templates;

class PWP_Template
{
    public $sourceFolder;

    public function __construct(string $folder = null)
    {
        if ($folder) {
            $this->set_folder($folder);
        }
    }

    public function set_folder(string $folder): void
    {
        $this->sourceFolder = rtrim($folder);
    }

    public function render(string $template, array $vars = []): string
    {
        $template =  "{$this->folder}/{$template}.php";
        $output = '';
        if ($template) {
            $output = $this->render_template($template, $vars);
        }
        return $output;
    }

    private function render_template(string $template, array $vars = []): string
    {
        ob_start();
        foreach ($vars as $key => $value) {
            ${$key} = $value;
        }
        include $template;
        return ob_get_clean();
    }
}
