# editor
Contains the classes related to the editor component of the plugin. The methods support the IMAXEL editor and Peleman Image Editor currently.

The components of this folder are tied into the `PWP_Ajax_Add_To_Cart` hookables.
TODO: 
## structure
there are X main components to the system, each with a separate component for the different editors.
### PWP_Editor_Project
class representing an existing editor project. Due to the quirks of URL redirection of the IMAXEL editor, the project class is also the source of a redirect URL to the editor project.
* PWP_PIE_Project
* PWP_IMAXEL_Project
### PWP_Product_Meta
class working as a metadata wrapper for Woocommerce Product objects. Used both for retrieving and updating meta data in the webshop.
* PWP_Product_Meta_Data
* PWP_Product_IMAXEL_Data
* PWP_Product_PIE_Data
### PWP_Abstract_Request
abstract requests aren't restricted to the editor component, but are required for making requests to the editor APIs. A request object HAS to be initialized and setup with a `PWP_Product_Meta_Data` object. A project request will return a `PWP_Editor_Project` object upon completion, or throw an error.
* PWP_New_PIE_Project_Request
* PWP_New_IMAXEL_Project_Request

