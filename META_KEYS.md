# PRODUCT META KEYS

the Peleman Webshop Package Plugin adds a series of meta keys to the standard Woocommerce Products, needed for its own operation and the connection with the Peleman Image Editor/Imaxel systems.

This is a reference of the keys added to the standard `WC_Product` objects by the plugin, for use both in development and internal use of the plugin, as well as a reference for F2D usage of the keys, their meaning, intent, and types.

These values can be adjust manually in the Woocommerce 'edit project' panel, or through the woocommerce Rest API as metadata values.
## general keys

### custom_variation_add_to_cart_label
`string`

Custom label for product's **add to cart** button, to replace the **add to cart** text.

The button label can be changed at different levels in the hierarchy, and will always use the lowest available value. the hierarchy goes as follows:

```Woocommerce > Plugin settings > simple/parent product label> variant product label```
___
### call_to_order  
`bool` - default `false`

  Wether the customer has to call to order the product.
  ___
### cart_price
`float`

legacy value from the PPI
___
### cart_units
`int`

legacy value from the PPI
___
### unit_code
`string`

legacy value from the PPI
___
### f2d_artcd
`string`

legacy value from the PPI. represent the product's article code within the Fly2Data system.
___
### pwp_editor_id
`string`

Valid values: `PIE`, `IMAXEL` or `null`

Represents the editor which is to be used for the customization of the project. if set to `empty`, `null`, `false`, `0` or another non-valid value, the plugin will assume the project cannot be edited. If no template ID is set, for either of the editors, the plugin will also assume the project cannot be edited.

## Peleman Image Editor
### pie_template_id
`string`

ID value for a template for the Peleman Image Editor. By convention, starts with `tpl`.
___
### pie_design_id
`string`

Not yet in use
___
### pie_color_code
`string`

color code for a project background
___
### pie_background_id
`string`

background id for a project
___
### pie_image_upload
`bool` - default `false`

Control value for the image uploader (uppy) component of the editor. If set to `true`, the user will be directed to an upload screen where they are prompted to upload images, before being redirected to the Peleman Image Editor itself.
___
### pie_format_id
`string`

format ID for PIE projects. controls templates and formats for photo books.

**Only relevant when `pie_image_upload` is set to `true`.**
___
### pie_num_pages
`int` - default `0`

amount of pages that the project is to generate, in case of a photobook.

**Only relevant when `pie_image_upload` is set to `true`.**
___
### pie_min_images
`int` - default `0`

minimum amount of images the user *must* upload to the upload page of the project. if the value is negative, the plugin will automatically set it to 0.

**Only relevant when `pie_image_upload` is set to `true`.**
___
### pie_max_images
`int` - default `0`
maximum amount of images the user *can* upload to the upload page of the project. If the value is negative, the plugin will automatically set it to 0.

**Only relevant when `pie_image_upload` is set to `true`.**
___
## Imaxel
### imaxel_template_id
`string`

Imaxel template ID for the product.
___
### imaxel_variant_id
`string`

Imaxel variant ID of the template, for the product.
___
