<?php

declare(strict_types=1);

namespace PWP\publicPage;

use IWPML_Current_Language;
use PWP\includes\utilities\PWP_Editor_Client;
use PWP\includes\hookables\PWP_Abstract_Ajax_Component;
use PWP\includes\utilities\clients\PWP_New_Project_Request;

class pwp_add_to_cart extends PWP_Abstract_Ajax_Component
{
    public function __construct()
    {
        parent::__construct(
            'pwp_add_to_cart_nonce',
            'pwp-ajax-add-to-cart',
            'ajax_add_to_cart',
            plugins_url('js/add-to-cart.js', __FILE__)
        );
    }

    public function callback(): void
    {
        $client = new PWP_Editor_Client('deveditor.peleman.com');

        $variant = wc_get_product((int)sanitize_text_field($_GET['variant']));
        $template_id = $variant->get_meta('template_id', true, 'view');
        $variant_id = $variant->get_meta('variant_code', true, 'view');

        $request = new PWP_New_Project_Request((string)get_current_user_id(), $template_id, wc_get_cart_url());
        if (defined('ICL_LANGUAGE_CODE')) {
            $request->set_language(ICL_LANGUAGE_CODE);
        }

        //TODO: better validation. check if these actually exist in the database
        $templateIsValid = str_contains($template_id, 'tpl');
        $variantIsValid = str_contains($variant_id, 'var');

        $content_file_id = sanitize_text_field($_GET['content']);

        if (!$templateIsValid || !$variantIsValid) {
            wp_send_json(array(
                'status' => 'error',
                'message' => 'variant does not have proper template data!',
            ));
            return;
        }
        $language = 'en';

        $projectId = $client->create_new_project($request);

        // $destination = $client->get_new_project_url($template_id, $variant_id, $language);
        $destination = 'https://deveditor.peleman.com/?projecturl=pie/projects/625e933128f37/var133714.json';
        wp_send_json(array(
            'status' => 'success',
            'message' => 'all is well',
            'isCustomizable' => true,
            'project_data' => $projectId,
            'destinationUrl' => $destination,
        ), 200);
        return;
    }

    public function callback_nopriv(): void
    {
        $this->callback();
    }
}
