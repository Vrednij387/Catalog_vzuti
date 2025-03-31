Thank you for using our module. For the best user experience we provide some information how to use ths module.
If you need more help, please feel free to contact us.


Installation

The module can be installed in the same way that any PrestaShop module.

1. Via PrestaShop back office
 - Navigate to the "Modules and Services" in the admin.
 - In the upper-right hand corner click "Add a new module", the upload form will visible.
 - Select the compressed module file (.zip, .tar, .tar.gz, .tgz) by click "Choose a file" and then click "Upload this module".
 - Currently the module is uploaded, now click "Install" on the uploaded module.
 - Now the module is installed and you can start using it.

2. Via FTP
 - Upload the uncompressed module folder to the "modules" directory of your store.
 - Navigate to the "Modules and Services" in the admin.
 - Find the uploaded module in the module list and click "Install".
 - Now the module is installed and you can start using it.


Getting Started


This module generates meta tags automatically from plenty of information through keyword variables
like {product_name}, this keyword displays the product's name.
The keywords named properly, so you will know what they going to display.
Under every generator field, you can find the available keywords for the current field.
Just click Show/Hide and the available keywords show up. If you click on the selected keyword,
it will insert into the attached generator field at cursor position.

You are able to override the automatically generated meta tag by filling the desired content's meta field.
E.g.: If you want to write a specific meta title to one of your products, navigate to the product edit
section in the admin, click on the SEO tab and fill the meta fields. This is also useful when you
have a large catalog and you don't want bad meta fields, the automatic generator creates good meta
fields until you write even better manually. Because how the override working we implemented a maintenance
tool which can clear meta fields. For a fresh start, we recommend to clear the meta fields.

We also implemented a product friendly url generator which can generate much more relevant urls than the
built in version. This is also really useful when you use our Advanced SEO Friendly URLs module which
removes the product id from the url, with this tool you can generate relevant but unique friendly urls
which is required to the proper work. It generates the {rewrite} variable in the
Preferences -> SEO & URLs -> Schema of URLs (panel) -> Route to products (input). So you won't need to
add any other variables. IMPORTANT! For technical reason it can't be an automatic process, so you have to do this manually.

 
Product Settings
Example

Meta title generator schema: {product_name} - {default_category_name} - {manufacturer_name}

Meta description generator schema: {product_short_description} {default_category_meta_description}

Meta keywords generator schema: {product_name} {default_category_parent_categories} {manufacturer_name}
Customization

You are able to create different generator schemas by category. Under the "Product Meta Default Settings" 
from simply click the plus sign, then select at least one category where this schemas used.

Category Settings
Example

Meta title generator schema: {category_name} {ps_page_display} - {ps_shop_name}

Meta description generator schema: {category_description}

Meta keywords generator schema: {category_name} {category_parent_categories}
Important

Don't forget to add the {ps_page_display} variable, it helps prevent duplicate meta titles. This variable can 
configure in the General Settings tab.

Manufacturer Settings
Example

Meta title generator schema: {manufacturer_name} {ps_page_display} - {ps_shop_name}

Meta description generator schema: The best products from {manufacturer_name}.

Meta keywords generator schema: {manufacturer_name} {manufacturer_short_description}
Important

Don't forget to add the {ps_page_display} variable, it helps prevent duplicate meta titles. This variable can 
configure in the General Settings tab.

Supplier Settings
Example

Meta title generator schema: {supplier_name} {ps_page_display} - {ps_shop_name}

Meta description generator schema: The best products from our {supplier_name} supplier.

Meta keywords generator schema: {supplier_name} {supplier_description}
Important

Don't forget to add the {ps_page_display} variable, it helps prevent duplicate meta titles. This variable can 
configure in the General Settings tab.

CMS Settings
Example

Meta title generator schema: {cms_name} - {cms_category_parent_categories} - {ps_shop_name}

Meta description generator schema: Useful information about {cms_name}.

Meta keywords generator schema: {cms_name} {cms_category_name} {cms_category_meta_keywords} {cms_category_parent_categories}

CMS Category Settings
Example

Meta title generator schema: {cms_category_parent_categories} - {ps_shop_name}

Meta description generator schema: Useful information about {cms_category_name}.

Meta keywords generator schema: {cms_category_name} {cms_category_parent_categories}

These are some examples but the settings are only limited by your imagination.


ModuleFactory