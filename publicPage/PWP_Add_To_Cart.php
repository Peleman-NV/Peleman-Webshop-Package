<?php

declare(strict_types=1);

namespace PWP\publicPage;

use IWPML_Current_Language;
use PWP\includes\editor\PWP_PIE_Data;
use PWP\includes\editor\PWP_Editor_Client;
use PWP\includes\editor\PWP_New_PIE_Project_Request;
use PWP\includes\hookables\PWP_Abstract_Ajax_Component;

class pwp_add_to_cart extends PWP_Abstract_Ajax_Component
{
    public function __construct()
    {
        parent::__construct(
            'add_to_cart',
            plugins_url('js/add-to-cart.js', __FILE__),
        );
    }

    public function callback(): void
    {
        $client = new PWP_Editor_Client('deveditor.peleman.com');

        $pie_data = new PWP_PIE_Data((int)sanitize_text_field($_GET['variant']));
        $variant = $pie_data->get_parent();

        if ($pie_data->get_is_customizable()) {

            $request = new PWP_New_PIE_Project_Request((string)get_current_user_id(), $pie_data, wc_get_cart_url());
            if (defined('ICL_LANGUAGE_CODE')) {
                $request->set_language(ICL_LANGUAGE_CODE);
            }

            //TODO: handle PDF file uploads
            // $content_file_id = sanitize_text_field($_GET['content']);

            if (false) {
                wp_send_json(array(
                    'status' => 'error',
                    'message' => 'variant does not have proper template data!',
                ));
                return;
            }

            wp_send_json(array(
                'status' => 'error',
                'message' => $request->to_array()
            ));

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
        }

        wp_send_json(array(
            'status' => 'error',
            'message' => "foo"
        ));
    }

    public function callback_nopriv(): void
    {
        $this->callback();
    }
}
