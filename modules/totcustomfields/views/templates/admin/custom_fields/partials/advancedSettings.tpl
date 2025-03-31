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

<form id="totNewInputForm"
      class="form-horizontal"
      action="{$module.config_url|escape:'htmlall':'UTF-8'}"
      method="POST"
      enctype="multipart/form-data">

    <ps-switch 
           class="switch-multiple-product-tabs"
           name="TOTCUSTOMFIELDS_CUSTOMFIELDS_MULTIPLE_PRODUCT_TABS"
           label="{l s='Create a new tab for each custom field on the Product page :' mod='totcustomfields'}"
           yes="{l s='Yes' mod='totcustomfields'}" no="{l s='No' mod='totcustomfields'}"
           hint="{l s='If you want to create custom fields for each tab, you must select the hook ' mod='totcustomfields'}"
           active="{if isset($advancedSettings['TOTCUSTOMFIELDS_CUSTOMFIELDS_MULTIPLE_PRODUCT_TABS']) && $advancedSettings['TOTCUSTOMFIELDS_CUSTOMFIELDS_MULTIPLE_PRODUCT_TABS']}true{else}false{/if}"></ps-switch>

    <ps-textarea-lang
        id="totCustomFields-product-tab-title"
        name="TOTCUSTOMFIELDS_CUSTOMFIELDS_PRODUCT_TAB_TITLE" label="{l s='Common tab title' mod='totcustomfields'}"
                      hint="{l s='You can change the title for the module\'s additional tab on the product page by default.' mod='totcustomfields'}"
                      col-lg="10"
                      active-lang="{$id_current_lang|escape:'htmlall':'UTF-8'}">
        {foreach from=$languages item=lang}
            <div data-is="ps-textarea-lang-value"
                 iso-lang="{$lang.iso_code|escape:'htmlall':'UTF-8'}"
                 id-lang="{$lang.id_lang|escape:'htmlall':'UTF-8'}"
                 lang-name="{$lang.name|escape:'htmlall':'UTF-8'}"
            >{if isset($advancedSettings['TOTCUSTOMFIELDS_CUSTOMFIELDS_PRODUCT_TAB_TITLE'][$lang.id_lang])}{$advancedSettings['TOTCUSTOMFIELDS_CUSTOMFIELDS_PRODUCT_TAB_TITLE'][$lang.id_lang]|escape:'htmlall':'UTF-8'}{/if}</div>
        {/foreach}
    </ps-textarea-lang>
           
    <ps-panel-footer>
        <ps-panel-footer-submit
            title="{l s='Save settings' mod='totcustomfields'}"
            icon="process-icon-save"
            direction="right"
            name="saveAdvancedSettings">
        </ps-panel-footer-submit>
    </ps-panel-footer>
</form>