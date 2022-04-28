# CATEGORIES endpoints
* endpoint classes for product categories in woocommerce

        {{domain}}/wp-json/pwp/v1/categories

`GET` | `POST` | `DELETE` | `PUT` `PATCH` | `BATCH` 

___
## `GET` 

retrieve one or more product categories. a `slug` can be passed along to retrieve a specific category if desired. Otherwise, the endpoint will try and return all categories that match the input parameters

### parameters:
  * __lang__  
    > __`string`__ - language of categories. default is `en`.
___
## `POST` 
    
create a new category.

### parameters:

* __name__  
    > `REQUIRED`

    > __`string`__ - name of the category
* __slug__
    > `REQUIRED` 
    
    > __`string`__ - category slug. should not contain spaces and should be unique
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
