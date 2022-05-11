<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\utilities\schemas\PWP_Argument_Schema;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_CREATE_Endpoint;
use PWP\includes\handlers\commands\PWP_Category_Command_Factory;
use PWP\includes\utilities\PWP_SitePress_Wrapper;

class PWP_Categories_CREATE_Endpoint extends PWP_Abstract_CREATE_Endpoint
{
    public function __construct(string $path, PWP_I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $path,
            'product category',
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $factory = new PWP_Category_Command_Factory();
        $data = new PWP_Term_Data($request->get_body_params());
        $command = $factory->new_create_term_command($data);
        return new WP_REST_Response($command->do_action()->to_array());
    }

    function get_arguments(): array
    {
        $sitepress = new PWP_SitePress_Wrapper();
        $activeLanguages = $sitepress->get_active_languages();

        $schema = new PWP_Argument_Schema(new PWP_Schema_Factory(PWP_TEXT_DOMAIN));
        $schema->add_string_property(
            'name',
            'name of the category'
        )->required();
        $schema->add_string_property(
            'slug',
            "slug of the category. If not given, will create a new slug from the name. should not contain spaces"
        )->required();
        $schema->add_int_property(
            'parent_id',
            'id of the parent category, if applicable. will precede the parent_slug if present'
        );
        $schema->add_string_property(
            'parent_slug',
            'slug of the parent category, if applicable. will supercede the parent_id if present'
        );
        $schema->add_string_property(
            'english_slug',
            'slug of the default language category, used for uploading translated categories'
        );
        $schema->add_enum_property(
            'language_code',
            'language code of a translated category. should match the suffix of the slug if present',
            $activeLanguages
        )->default('en');
        $schema->add_string_property(
            'description',
            'description of the category'
        );
        $schema->add_enum_property(
            'display',
            'how the category is displayed in the archive',
            array(
                'default',
                'products',
                'subcategories',
                'both'
            )
        )->default('default');
        $schema->add_int_property(
            'image_id',
            'id of the image to be used with this category in displaying categories'
        );
        $seoData = $schema->add_array_property(
            'seo',
            'search engine optimization properties'
        );
        $seoData->add_string_property('focus_keyword', 'focus keyword for YOAST SEO');
        $seoData->add_string_property('description', 'description of the SEO data for YOAST SEO');;

        return $schema->to_array();
        return [];
    }
}
