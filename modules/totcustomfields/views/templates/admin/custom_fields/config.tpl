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

<div class="totcustomfields-configuration-wrapper loading">
    {if file_exists($module.folder_url|cat:'views/templates/admin/custom_fields/banner.tpl')}
        {include file='./banner.tpl' module=$module}
    {/if}
    <ps-tabs position="left">

        <ps-tab label="{l s='New custom field' mod='totcustomfields'}"
                active="true"
                id="tabFormInput"
                icon="icon-plus-square"
                {if isset($newInputData.id_input)}href="{$module.config_url|escape:'htmlall':'UTF-8'}"{/if}>
            {include file='./partials/formInput.tpl'}
        </ps-tab>

        {foreach from=$objects item=object}
            <ps-tab label="{$object.location_name|escape:'htmlall':'UTF-8'}"
                    class="tabObject"
                    id="tabObject{$object.code|escape:'htmlall':'UTF-8'}"
                    icon="icon-circle-o"
                    badge="{$object.countInputs|escape:'htmlall':'UTF-8'}">
                {if $object.configurationData}
                    {foreach from=$object.configurationData.displayMethods item=display}
                        {if $display.templatePath}
                            {include file=$display.templatePath display=$display}
                        {/if}
                    {/foreach}
                {else}
                    {l s='You have not created custom fields yet.' mod='totcustomfields'}
                {/if}
            </ps-tab>
        {/foreach}

        <ps-tab label="{l s='Advanced settings' mod='totcustomfields'}"
                id="tabAdvancedSettings"
                icon="icon-cog"
        >
            {include file='./partials/advancedSettings.tpl'}
        </ps-tab>
        {if file_exists($module.folder_url|cat:'views/templates/admin/custom_fields/seemore.tpl')}
            <ps-tab label="{l s='See more' mod='totcustomfields'}"
                    id="seemore" icon="icon-AdminParentModules"
                    img="../img/t/AdminBackup.gif">
                {include file='./seemore.tpl'}
            </ps-tab>
        {/if}
    </ps-tabs>

    <ps-panel icon="icon-cogs" header="{l s='Cache' mod='totcustomfields'}">
        <form class="form-horizontal" action="{$module.config_url|escape:'htmlall':'UTF-8'}" method="POST">

            {l s='This will delete all data cached by the module. Use this if your modifications are not displayed on your shop.' mod='totcustomfields'}

            <ps-panel-footer>
                <ps-panel-footer-submit
                        title="{l s='Delete cache' mod='totcustomfields'}"
                        icon="process-icon-save"
                        direction="right"
                        name="deleteCache">
                </ps-panel-footer-submit>
            </ps-panel-footer>

        </form>
    </ps-panel>

    <ps-alert-hint>
        <b>{l s='How does it work?' mod='totcustomfields'}</b><br>
        {l s='Create a new field via module configurations and fill it via Product, Category or Order page (depending on your custom field location).' mod='totcustomfields'}
        <br>{l s='The corresponding value will be shown in the FrontOffice.' mod='totcustomfields'}
        <br> {l s='You can also edit, disable or delete created custom fields: just go to 2nd, 3st or 4st tab of Module configurations.' mod='totcustomfields'}
        <br> {l s='For editing fields values (the content of fields that will be shown in the FrontOffice) and accessing to summary you should go to "Custom fileds" tab in left Prestashop menu. There are 3 types of summary pages: My products fields, My categories fields, My orders fields. Here you can find already filled fields, thus you can quickly access to the corresponding page for updating fields.' mod='totcustomfields'}
        <br><br><i>{l s='Please see the module details or ducumentation for further details. You can also contact our support team for any questions.' mod='totcustomfields'}</i>
    </ps-alert-hint>

    <div class="loader-wrapper">
        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</div>

{addJsDef totcustomfields_languages=$languages}
{addJsDef totcustomfields_deleteInput_confirmation=$deleteInputConfirmation}
{addJsDef PS_VERSION='1.7'}