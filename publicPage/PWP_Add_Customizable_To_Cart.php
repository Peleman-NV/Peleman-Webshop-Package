<?php

declare(strict_types=1);

namespace PWP\publicPage;

use IWPML_Current_Language;
use PWP\includes\editor\PWP_PIE_Data;
use PWP\includes\editor\PWP_Editor_Client;
use PWP\includes\editor\PWP_PIE_Create_Project_Request;
use PWP\includes\hookables\PWP_Abstract_Ajax_Component;

class pwp_add_Customizable_to_cart extends PWP_Abstract_Ajax_Component
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

        if ($pie_data->get_is_customizable()) {

            //TODO: handle data properly.
            $request = new PWP_PIE_Create_Project_Request(
                (string)get_current_user_id(),
                $pie_data,
                wc_get_cart_url()
            );

            $request->set_language(defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en');
            $request->set_project_name("k" . uniqid());
            $request->set_editor_instructions(
                USE_DESIGN_MODE,
                USE_BACKGROUNDS,
                USE_DESIGNS,
                SHOW_CROP_ZONE,
                SHOW_SAFE_ZONE,
                USE_TEXT,
                USE_ELEMENTS,
                USE_DESIGNS,
                USE_OPEN_FILE
            );

            //TODO: how to handle project names? let users define them on the editor side? use random UUID?
            //      right now generate a random default name.
            //      need to do the request in an authenticated manner: use an API key?
            //      requires a response: response should allow me to redirect the user to their editor page.


            //TODO: handle PDF file uploads
            // $content_file_id = sanitize_text_field($_GET['content']);

            $projectId = $client->create_new_project($request);
            wp_send_json(array(
                'status' => 'error',
                'message' => $projectId,
            ));

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