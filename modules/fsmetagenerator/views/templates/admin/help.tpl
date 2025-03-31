{**
 * Copyright 2023 ModuleFactory
 *
 * @author    ModuleFactory
 * @copyright ModuleFactory all rights reserved.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *}

<div id="fsmg_help" class="panel">
    <div class="panel-heading">
        <span>{l s='Help' mod='fsmetagenerator'}</span>
    </div>
    <div class="form-wrapper clearfix">
        Thank you for using our module. For the best user experience we provide some examples and information.
        If you need more help, please feel free to contact us.
        <br />
        <h2>General Information</h2>
        <p>
            This module generates meta tags automatically from plenty of information through keyword variables like <strong>{literal}{product_name}{/literal}</strong>,
            this keyword displays the product's name.<br />
            The keywords named properly, so you will know what they going to display.<br />
            Under every generator field, you can find the available keywords for the current field. Just click <strong>Show/Hide</strong> and the
            available keywords show up. If you click on the selected keyword, it will insert into the attached generator field at cursor position.<br />
            <br />
            You are able to override the automatically generated meta tag by filling the desired content's meta field. E.g.: If you want to write a specific
            meta title to one of your products, navigate to the product edit section in the admin, click on the <strong>SEO</strong> tab and fill the meta
            fields. This is also useful when you have a large catalog and you don't want bad meta fields, the automatic generator creates good meta fields
            until you write even better manually. Because how the override working we implemented a maintenance tool which can clear meta fields.
            For a fresh start, we recommend to clear the meta fields.<br />
            <br />
            For major content types: Product, Category, Manufacturer and Supplier there are manual meta generators, which are saves the generated meta information
            based on the schema you set up.<br />
            <br />
            We also implemented a product friendly url generator which can generate much more relevant urls than the built in version. This is also really
            useful when you use our <strong>Advanced SEO Friendly URLs</strong> module which removes the product id from the url, with this tool you can generate
            relevant but unique friendly urls which is required to the proper work. It generates the <strong>{literal}{rewrite}{/literal}</strong> variable
            in the Preferences -> SEO & URLs -> Schema of URLs (panel) -> Route to products (input). So you won't need to add any other variables.
            <strong>IMPORTANT!</strong> For technical reason it can't be an automatic process, so you have to do this manually.
        </p>
        <br />

        <h2>Product Settings</h2>
        <h4>Example</h4>
        <p>
            <strong>Meta title generator schema:</strong> <span>{literal}{product_name} - {default_category_name} - {manufacturer_name}{/literal}</span>
        </p>
        <p>
            <strong>Meta description generator schema:</strong> <span>{literal}{product_short_description} {default_category_meta_description}{/literal}</span>
        </p>
        <p>
            <strong>Meta keywords generator schema:</strong> <span>{literal}{product_name} {default_category_parent_categories} {manufacturer_name}{/literal}</span>
        </p>
        <h4>Customization</h4>
        <p>
            You are able to create different generator schemas by category. Under the "Product Meta Default Settings" from simply click
            the plus sign, then select at least one category where this schemas used.
        </p>
        <br />

        <h2>Category Settings</h2>
        <h4>Example</h4>
        <p>
            <strong>Meta title generator schema:</strong> <span>{literal}{category_name} {ps_page_display} - {ps_shop_name}{/literal}</span>
        </p>
        <p>
            <strong>Meta description generator schema:</strong> <span>{literal}{category_description}{/literal}</span>
        </p>
        <p>
            <strong>Meta keywords generator schema:</strong> <span>{literal}{category_name} {category_parent_categories}{/literal}</span>
        </p>
        <h4>Important</h4>
        <p>
            Don't forget to add the <strong>{literal}{ps_page_display}{/literal}</strong> variable, it helps prevent duplicate meta titles. This
            variable can configure in the <strong>General Settings</strong> tab.
        </p>

        <br />
        <h2>Manufacturer Settings</h2>
        <h4>Example</h4>
        <p>
            <strong>Meta title generator schema:</strong> <span>{literal}{manufacturer_name} {ps_page_display} - {ps_shop_name}{/literal}</span>
        </p>
        <p>
            <strong>Meta description generator schema:</strong> <span>{literal}The best products from {manufacturer_name}.{/literal}</span>
        </p>
        <p>
            <strong>Meta keywords generator schema:</strong> <span>{literal}{manufacturer_name} {manufacturer_short_description}{/literal}</span>
        </p>
        <h4>Important</h4>
        <p>
            Don't forget to add the <strong>{literal}{ps_page_display}{/literal}</strong> variable, it helps prevent duplicate meta titles. This
            variable can configure in the <strong>General Settings</strong> tab.
        </p>

        <br />
        <h2>Supplier Settings</h2>
        <h4>Example</h4>
        <p>
            <strong>Meta title generator schema:</strong> <span>{literal}{supplier_name} {ps_page_display} - {ps_shop_name}{/literal}</span>
        </p>
        <p>
            <strong>Meta description generator schema:</strong> <span>{literal}The best products from our {supplier_name} supplier.{/literal}</span>
        </p>
        <p>
            <strong>Meta keywords generator schema:</strong> <span>{literal}{supplier_name} {supplier_description}{/literal}</span>
        </p>
        <h4>Important</h4>
        <p>
            Don't forget to add the <strong>{literal}{ps_page_display}{/literal}</strong> variable, it helps prevent duplicate meta titles. This
            variable can configure in the <strong>General Settings</strong> tab.
        </p>

        <br />
        <h2>CMS Settings</h2>
        <h4>Example</h4>
        <p>
            <strong>Meta title generator schema:</strong> <span>{literal}{cms_name} - {cms_category_parent_categories} - {ps_shop_name}{/literal}</span>
        </p>
        <p>
            <strong>Meta description generator schema:</strong> <span>{literal}Useful information about {cms_name}.{/literal}</span>
        </p>
        <p>
            <strong>Meta keywords generator schema:</strong> <span>{literal}{cms_name} {cms_category_name} {cms_category_meta_keywords} {cms_category_parent_categories}{/literal}</span>
        </p>

        <br />
        <h2>CMS Category Settings</h2>
        <h4>Example</h4>
        <p>
            <strong>Meta title generator schema:</strong> <span>{literal}{cms_category_parent_categories} - {ps_shop_name}{/literal}</span>
        </p>
        <p>
            <strong>Meta description generator schema:</strong> <span>{literal}Useful information about {cms_category_name}.{/literal}</span>
        </p>
        <p>
            <strong>Meta keywords generator schema:</strong> <span>{literal}{cms_category_name} {cms_category_parent_categories}{/literal}</span>
        </p>
        <br />
        These are some examples but the settings are only limited by your imagination.<br />
    </div>
</div>