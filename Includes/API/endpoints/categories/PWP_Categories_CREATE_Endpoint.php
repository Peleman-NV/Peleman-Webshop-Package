<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_Term;
use WP_REST_Request;
use WP_REST_Response;


use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\utilities\schemas\PWP_Argument_Schema;
use PWP\includes\API\endpoints\PWP_Abstract_CREATE_Endpoint;
use PWP\includes\exceptions\PWP_API_Exception;

class PWP_Categories_CREATE_Endpoint extends PWP_Abstract_CREATE_Endpoint
{
    public function __construct(string $path, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            $path . "/categories",
            'category',
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $handler = new PWP_Category_Handler();
            $response = $handler->create_item($request->get_body_params());

            if ($response instanceof WP_Term) {
                return new WP_REST_RESPONSE($response->data);
            }
        } catch (PWP_API_Exception $exception) {
            return $exception->to_rest_response();
        } catch (\Exception $exception) {
            return new WP_REST_Response(array(
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ), $exception->getCode());
        }
    }

    function get_arguments(): array
    {
        $factory = new PWP_Schema_Factory(PWP_TEXT_DOMAIN);
        $schema = new PWP_Argument_Schema();
        $schema
            ->add_property(
                'name',
                $factory->string_property('name of the category')
                    ->required()
            )->add_property(
                'slug',
                $factory->string_property("slug of the category. If not given, will create a new slug from the name. should not contain spaces")
            )->add_property(
                'parent_id',
                $factory->int_property('id of the parent category, if applicable. will precede the parent_slug if present')
            )->add_property(
                'parent_slug',
                $factory->string_property('slug of the parent category, if applicable. will supercede the parent_id if present')
            )->add_property(
                'english_slug',
                $factory->string_property('slug of the default language category, used for uploading translated categories')
            )->add_property(
                'language_code',
                $factory->enum_property(
                    'language code of a translated category. should match the suffix of the slug if present',
                    array(
                        'en',
                        'es'
                    )
                )
            )->add_property(
                'description',
                $factory->string_property('description of the category')
            )->add_property(
                'display',
                $factory->enum_property(
                    'how the category is displayed in the archive',
                    array(
                        'default',
                        'products',
                        'subcategories',
                        'both'
                    )
                )
                    ->default('default')
            )->add_property(
                'image_id',
                $factory->int_property('id of the image to be used with this category in displaying categories')
            )
            ->add_property(
                'seo',
                $factory->array_property('search engine optimization properties')
                    ->add_property(
                        'focus_keyword',
                        $factory->string_property('focus keyword for YOAST SEO')
                    )
                    ->add_property(
                        'description',
                        $factory->string_property('description of the SEO data for YOAST')
                    )
            );

        return $schema->to_array();
    }
}
