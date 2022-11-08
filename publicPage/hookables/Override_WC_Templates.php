<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Override_WC_Templates extends Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_locate_template', 'override_wc_template', 10, 3);
    }

    public function override_wc_template(string $template, string $templateName, string $templatePath): string
    {
        switch (basename($template, '.php')) {
            case 'simple':
                return trailingslashit(plugin_dir_path(__FILE__)) . '../../templates/woocommerce/simple.php';
            case 'variation-add-to-cart-button':
                return trailingslashit(plugin_dir_path(__FILE__)) . '../../templates/woocommerce/variation-add-to-cart-button.php';
                //TODO: add more cases for other templates we override
            default:
                return $template;
        }
    }
}