{**
 * Copyright since 2022 totcustomfields
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to tech@202-ecommerce.com so we can send you a copy immediately.
 *
 * @author    202 ecommerce <tech@202-ecommerce.com>
 * @copyright 2022 totcustomfields
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 *}

<div class="col-lg-9 col-lg-offset-3">
    {l s='You can easily add this new input to your current template by using this "shortcode".' mod='totcustomfields'}
    <br/>
    {l s='We recommend you paste it in the following file : ' mod='totcustomfields'} {$displayData.objectTemplateFile|escape:'htmlall':'UTF-8'}
    <input type="text"
           id="totCustomFields-smarty-shortcode"
           value=""
           readonly/>
</div>