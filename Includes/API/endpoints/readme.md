# endpoints
folder containing the individual endpoint classes for the API.

## PWP_I_Endpoint
interface for API endpoints

## PWP_Endpoint
abstract class for API endpoints. Contains necessary functionality for the proper operation of all endpoints.

API Endpoints can have up to five endpoints:
### __GET__
retrieve multiple or a single item at the endpoint
* get_item(args);
* get_items(identifier, args);

### __POST__
create a new item at the endpoint
* create_item(identifier, args);

### __PUT__/__PATCH__
update an existing item at the endpoint
* update_item(identifier, args);

### __DELETE__
delete an existing item at the endpoint
* delete_item(identifier, args);

### __BATCH__
batch items to post/update/delete. goes up to 100 items max
* batch items(args);

as well as a matchin method for each to authorize/authenticate each route, and functions to call/generate item schemas

___
# concrete endpoints

## PWP_Test_Endpoint
- Returns a simple confirmation message when called. has no other purpose than to confirm the proper operation of the API system.

        <domain>/wp-json/ppa/v1/test

    `GET` | `POST` | `PUT` | `PATCH` | `DELETE`

___
## PWP_Products_Endpoint
- Returns one or multiple products.

        <domain>/wp-json/ppa/v1/products/?id

    `GET` | `POST`

___
## PWP_Variations_Endpoint
- Returns one or multiple products.

        <domain>/wp-json/ppa/v1/products/:id/variations/?id

    `GET` | `POST`

___
## PWP_Tags_Endpoint
- endpoint for woocommerce tags

        <domain>/wp-json/ppa/v1/tags/?id

    `GET` | `POST` | `PUT` | `PATCH` | `DELETE`

___
## PWP_Attributes_Endpoint
- endpoint for woocommerce attributes

        <domain>/wp-json/ppa/v1/attributes

    `GET` | `POST`

___
## PWP_Terms_Endpoint
- Returns one or multiple products.

        <domain>/wp-json/ppa/v1/terms

    `GET` | `POST`

___
## PWP_Images_Endpoint
- Returns one or multiple products.

        <domain>/wp-json/ppa/v1/Images

    `GET` | `POST`

___
## PWP_Categories_Endpoint
- Returns one or multiple products.

        <domain>/wp-json/ppa/v1/categories

    `GET` | `POST` | `BATCH`
    ___
    `POST` parameters:

    * __name__   `REQUIRED`
        > __`string`__ - name of the category
    * __slug__
        > __`string`__ - category slug. should not contain spaces
    * __parent_id__
        > __`int`__ - id of the parent category. will precede the parent-slug if present
    * __parent_slug__
        > __`string`__ - slug of the parent category. will supercede the __parent_id__ if it is present       
    * __english_slug__
        > __`string`__ - slug of the default language category, used for uploading translated categories
    * __language_code__
        > __`string`__ - language code of a translated category. should match the suffix of the slug if present

            accepted values will generally be two-character strings, dependant on the local WPML pluging settings. if the matching language code isn't found in the installed languages, an error will be thrown.
            ie. 

            en
            es
            nl
            fr
            ge

            etc.
    * __description__
        > __`string`__ - description of the category
    * __display__
        > __`string`__ - how the category is displayed in the archive

            default - default value if left empty
            products - display as products
            subcategories - display as subcategories
            both - display as both products and subcategories

    * __image_id__
        > __`int`__ - id of the image to be used with this category in displaying categories
    * __seo__
        > __`array`__
        * __focus_keyword__
            > __`string`__ - focus keyword for YOAST SEO
        * __description__
            > __`string`__ - description of the SEO data for YOAST

___
### PWP_Menus_Endpoint
- Returns menus/mega menus

        <domain>/wp-json/ppa/v1/menus

    `GET`

