# PRODUCTS API Endpoints
API Endpoint for uploading, updating, and deleting products & product variations.
    {{domain}}/wp-json/pwp/v1/products

## Available Endpoints
* [ ] CREATE - `POST` - create new product
* [ ] DELETE - `DELETE` - delete existing product
* [ ] FIND - `GET` - get existing products without parameter
* [ ] READ - `GET` - get existing products with parameter
* [ ] UPDATE - `PUT\PATCH` - updating existing product
* [ ] BATCH - `POST` - create, update, and delete products in a single batched call.

## CREATE - `POST`
Create an individual new product in woocommerce.
### parameters

#### name
`string` - `REQUIRED`

Name of the product being created, will be displayed in the store page
___
#### sku
`string` - `REQUIRED`

Product specific SKU for use within woocommerce. Must be unique within woocommerce. **Not required for Variable products.**
___
#### parent_sku
`string` - `REQUIRED` for **variations**

SKU of the parent of this product. Only required for `variations` to be properly configured as children of a `variable` product. 
___
#### f2d_sku
`string` - `REQUIRED`

Fly2Data specific SKU value. No particular use within woocommerce, but required for orders to be understood properly by Fly2Data
___
#### f2d_article_code
`string`
___
#### add_to_cart_label
`string`

Custom text to override the standard "add to cart" button text in Woocommerce. The custom label for a product variation will take precedence over the custom label of the variable parent product. If none is defined, the add to cart label will use the one defined by woocommerce.
___
#### call_to_order
`boolean` - default `false`

Wether the product page should disable the standard add to cart button and instead display a field with the telephone contact details of Peleman, so the customer can order the product in question.
___
#### type
`string` - default `simple`

Which type of product is to be created. Accepted values are

* `simple` - simple product
* `variable` - variable product, will have **variations**
* `variation` - variation product, will have a **variable** parent product
___
#### lang
`string` - default `en`

for translation purposes within WooCommerce using WPML. The language code should be an [ISO 639-1 code](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes). The default language for the API and most webshops will be English (en). In order for proper product parenting, ensure that the product's *sku* and *f2d_sku* match. Ideally, most information will be the exact same, with the exception of display strings such as the name and descriptions.
___
#### status
`string` - default `publish`

Status of the product upon creation. Accepted values are

* `draft` - product is in draft mode, and not visible on the webshop
* `pending` - product is pending
* `private` - product is private
* `publish` - product is published, visible and available to visitors
___
#### featured
`bool` - default `false`

Wether this product should be a featured product or not.
___
#### catalog_visibility
`string` - default `hidden`

Catalog visibility options. Accepted values are

* `visible`
* `catalog`
* `search`
* `hidden`
___
#### description
`string`

Complete product description for display on the product page in the webshop.
___
#### short_description
`string`

Short product description
___
#### regular_price
`float` - `REQUIRED`

Regular product price.
___
#### tax_status
`string` - default `taxable`

Tax status of the product. For use in internal Woocommerce Tax Calculations. Accepted values are:
* `none`
* `taxable`
* `shipping`
___
#### tax_class
`string`

Product's tax class. Who knows what this means.
___
#### sold_individually
`bool` - default `false`

Wether any given order can only contain **one** instance of the product.
___
#### weight
`float`

Product weight in *kg*.
___
#### dimensions
`object`

Product dimensions object. See the Dimensions Properties component.
___
#### upsell_skus
`array` - `string`

SKUs of upsell products.
___
#### cross_sell_skus
`array` - `string`

SKUs of cross-sell products.
___
#### purchase_note
`string`

Optional note to send the customer after purhcase of this product.
___
#### categories
`array` - `string`

Array of product category slugs.
___
#### tags
`array` - `string`

Array of product tag slugs.
___
#### images
`array`
***TO BE DONE***
___
#### menu_order
`int`

Menu order, used for custom product sorting.
___
#### editor_id
`string` - default `NONE`

Editor ID for the product. Determines wether the product is customizable, and which editor to use for customization.
Accepted values are:

* `NONE` - no editor, product is not customizable
* `PIE` - **Peleman Image Editor**, will use Peleman Image Editor data and functionality
* `IMAXEL` - **Imaxel Editor**, being phased out, only here for legacy products and compatibility purposes. Will use Imaxel Editor data and functionality.
___
#### pie_settings
`object` - `RECOMMENDED` if using the **Peleman Image Editor**

Object containing product-specific data required for proper communication and usage of the Peleman Image Editor. See the pie_settings component.
___
#### imaxel_settings
`object` - `RECOMMENDED` if using the **Imaxel Editor**

Object containing product specific data required for proper communication and usage of the Imaxel Editor. See the Imaxel_settings component.
___
#### price_per_page
`float`

For products that require a PDF upload or have editable contents through an editor. Additional cost to be charged for each page. A single piece or sheet of paper constitutes 2 pages.
___
#### base_page_count
`int`

**TO BE DONE**
___
#### unit_price
`float`

**TO BE DONE**
___
#### unit_amount
`int`

**TO BE DONE**
___
#### unit_code
`string`

**TO BE DONE**
___
#### pdf_upload
`object`

object of PDF upload data. See the pdf_upload component.
___
#### meta_data
`array` - `object`

Array of Meta Data objects. See the meta_data component.
___

### Dimensions Properties
`object`
Dimensions component.
#### length
`float`

Length of the product in *mm*.
___
#### height
`float`

Height of the product in *mm*.
___
#### width
`float`

Width of the product in *mm*.
___
### PIE PROPERTIES
`object`

Object of Peleman Image Editor specific settings.

#### template_id
`string` - `REQUIRED`

Template ID specific to the Peleman Image Editor.
___
#### design_id
`string`

Design ID specific to this product. **under development, may be subject to change in near future**
___
#### min_images
`int` - default `0`

Minimum image count for the product. Default value is 0, meaning no images are required. This value should never be higher than the `max_images` count. If the two values are the same, we can assume a *precise* number of images is required.
___
#### max_images
`int` - default `0`

Maximum image count for the product. Default value is 0, meaning the limit of images that the customer can upload is only restricted by the editor. This value should **never** be lower than the `min_images` count. If the two values are the same, we can assume a *precise* number of images is required.
___
#### color_code
`string`

Standardized color code (hex or internal color code system of Peleman?)
___
#### background_id
`string`

Product specific background id
___
#### format_id
`string`

For photo books, the specific format id for internal layouts.
___
#### pages_to_fill
`int`

For products with customizable contents, the amount of pages that the customer should be able to fill with their own contents.
___
#### editor_instructions
`array` - `string`

Instructions for launching and controlling the editor. Should be an array of individual strings, each of wich is a valid PIE editor instruction. See the PIE documentation for a list of valid editor instructions.

No internal validation of these instructions happen within the webshop, as these are not the domain of the webshop.
___
### IMAXEL PROPERTIES
`object`
Similar to the `pie_settings`, an object containing Imaxel product specific data.
#### template_id
`string` - `REQUIRED`

Template ID of the product to be used within Imaxel.
___
#### variant_code
`string`

Variant ID of the template variation to be used for this product.
___
### PDF UPLOAD PROPERTIES
`object`

#### requires_upload
`bool` - default `false`

Wether this product requires\allows a customer PDF upload before allowing an order. 
___
#### min_pages
`int`

Minimum pages that the uploaded PDF is allowed to contain.
___
#### max_pages
`int`

Maximum pages that the uploaded PDF is allowed to contain.
___
#### page_width
`float`

Permitted page width in *mm*.
___
#### page_height
`float`

Permitted page height in *mm*.
___
### Meta Data Properties
`object`

object representing a product meta-data key-value pair.
#### key
`string` - `REQUIRED`

key of the meta data value.
___
#### value
`mixed` - `REQUIRED`

string value of the meta data value. Recommended property types are
* `bool`
* `int`
* `float`
* `string`
Other property types are allowed, but can cause problems due to the serialization of the specific value in the database, which can cause issues when reading the value at a later point.
___
___

example json:

    {
        "name": "my_simple_product",
        "sku": "PRD123456",
        "parent_sku": "",
        "f2d_sku": "PRD123456",
        "f2d_article_code": "f2d_art_code",
        "add_to_cart_label": "click to buy!",
        "call_to_order": false,
        "type": "simple",
        "lang": "en",
        "status": "publish",
        "featured": false,
        "catalog_visibility": "hidden",
        "description": "this is a simple product uploaded through the api",
        "short_description": "this is a simple product",
        "regular_price": "12.99",
        "tax_status": "taxable",
        "tax_class": "idk",
        "sold_individually": false,
        "weight": 10.50,
        "dimensions": {
            "length": 2,
            "height": 2,
            "width": 2
        },
        "upsell_skus": [
            "PRD11111",
            "PRD11112"
        ],
        "cross_sell_skus": [
            "PRD11113"
        ],
        "purchase_note": "thanks for buying",
        "categories": [
            "my_category_1",
            "my_category_2",
            "my_category_3"
        ],
        "tags": [
            "tag 1",
            "tag 2",
            "tag 3"
        ],
        "images": [],
        "menu_order": -1,
        "editor_id": "",
        "pie_settings": {
            "template_id": "tpl123456",
            "design_id": "tpl123456_1",
            "min_images": 0,
            "max_images": 0,
            "color_code": "#121212",
            "background_id": "bckg_123",
            "format_id": "frm_123456",
            "pages_to_fill": 12,
            "editor_instructions": [
                "USE_DESIGN_MODE",
                "USE_BACKGROUNDS",
                "USE_UPLOADS"
            ]
        },
        "imaxel_settings": {
            "template_id": "123456",
            "variant_code": "654321"
        },
        "price_per_page": 0.02,
        "base_page_count": 1,
        "unit_price": 12.00,
        "unit_amount": 2,
        "unit_code": "654321",
        "pdf_upload": {
            "requires_upload": true,
            "min_pages": 0,
            "max_pages": 0,
            "page_width": 210,
            "page_height": 297
        },
        "meta_data": [
            {
                "key": "pie_is_tasty",
                "value": true
            },
            {
                "key": "imaxel_works",
                "value": "maybe"
            }
        ]
    }

