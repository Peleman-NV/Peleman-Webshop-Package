# WORDPRESS PLUGIN REVIEW

## TO DO

* [x] **incorrect stable tag**
  * property of the Plugin's `readme.txt`
  * *updated. Make sure value stays up to date with the latest stable branch of the project.*
* [x] **replace CURL with Wordpress HTTP API**
  * [x] `New_PIE_Project_Request.php`
  * [x] `Set_PIE_Project_As_Completed.php`
    * created new class, `Complete_PIE_Project_Request` to handle request.
  * [x] `FIND_Project_Thumbnail.php`
  * [x] `PIE_GET_Queue_Request.php`
    * Quick working fix implemented. need to bring the request in line with the `Abstract_PIE_request` it inherits from.
* [x] **Calling core files directly**
  * *offending file removed.*
* [x] **Data sanitization**
  * [x] `Ajax_Add_To_Cart.php`
  * [x] `Ajax_Load_Cart_Thumbnail.php`
  * [x] `Ajax_Show_Variation.php`
  * [x] `Ajax_Upload_PDF.php`
  * [x] `TEST_OAuth2_Client_Endpoint.php`
    * simply removed file, it has served its purpose.
  * [x] `Add_Custom_Project_On_Return.php`
  * [x] `Add_PDF_Contents_To_Cart.php`
  * [x] `Save_Parent_Product_Custom_Fields.php`
  * [x] `Save_Variable_Product_Custom_Fields.php`
  * [x] `PWP_Add_To_Cart_Button.php`
    * obsolete file. removed.
  * [x] `variation-add-to-cart-button.php`
  * [x] `New_PIE_Project_Request.php`
  * [x] `Set_PIE_Project_As_Completed.php`
    * [x] rework to move logic to an `abstract_PIE_request.php` inheritor class
  * [x] `FIND_Project_Thumbnail.php`
  * [x] `PIE_GET_Queue_Request.php`
* [x] **Out of Date libraries**
  * [x] `mustache`
* [x] **Calling file locations poorly**
  * [x] `Admin_Enqueue_SCripts.php`
  * [x] `Admin_Enequeue_Styles.php`
  * [x] `Ajax_Add_To_Cart.php`
  * [x] `Ajax_Load_Cart_Thumbnail.php`
  * [x] `Ajax_Show_Variation.php`
  * [x] `Ajax_Upload_PDF.php`
  * [x] `Ajax_Enqueue_Public_Styles.php`
  * [x] `Enqueue_Public_Styles.php`
* [x] **not using PHP short tags**
  ___
  
