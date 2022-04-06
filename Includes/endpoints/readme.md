# endpoints
folder containing the individual endpoint classes for the API.

## PWP_IEndpoint
interface for API endpoints

## PWP_Endpoint
abstract class for API endpoints. Contains necessary functionality for the proper operation of all endpoints.

___
## concrete endpoints

### PWP_Test_Endpoint
- Returns a simple confirmation message when called. has no other purpose than to confirm the proper operation of the API system.

        <domain>/wp-json/ppa/v1/test

    `GET` | `POST` | `PUT` | `PATCH` | `DELETE`

___
### PWP_Products_Endpoint
- Returns one or multiple products.

        <domain>/wp-json/ppa/v1/products/?id

    `GET` | `POST`

___
### PWP_Variations_Endpoint
- Returns one or multiple products.

        <domain>/wp-json/ppa/v1/products/:id/variations/?id

    `GET` | `POST`

___
### PWP_Tags_Endpoint
- endpoint for woocommerce tags

        <domain>/wp-json/ppa/v1/tags/?id

    `GET` | `POST` | `PUT` | `PATCH` | `DELETE`

___
### PWP_Attributes_Endpoint
- endpoint for woocommerce attributes

        <domain>/wp-json/ppa/v1/attributes

    `GET` | `POST`

___
### PWP_Terms_Endpoint
- Returns one or multiple products.

        <domain>/wp-json/ppa/v1/terms

    `GET` | `POST`

___
### PWP_Images_Endpoint
- Returns one or multiple products.

        <domain>/wp-json/ppa/v1/Images

    `GET` | `POST`

___
### PWP_Categories_Endpoint
- Returns one or multiple products.

        <domain>/wp-json/ppa/v1/categories

    `GET` | `POST`

___
### PWP_Menus_Endpoint
- Returns menus/mega menus

        <domain>/wp-json/ppa/v1/menus

    `GET`

