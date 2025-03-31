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
 * @author	 202 ecommerce <tech@202-ecommerce.com>
 * @copyright 2022 totcustomfields
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 *}

<ps-panel icon="icon-cogs"
          img="../img/t/AdminBackup.gif"
          header="{l s='These modules might interest you' mod='totcustomfields'}">
    <p>{l s='Choose another module developed by 202 for your e-commerce store is to choose a perfect integration of all the essential functionalities to manage your stocks.' mod='totcustomfields'}</p>
    <div class="row">
        {foreach from=$seemore.recommended item=module}
            <div class="col-sm-6 col-xs-12">
                <fieldset class="totmodule">
                    <div class="panel-body panel">
                        <div class="totmodule_img">
                            <img src="{$seemore.path|escape:'htmlall':'UTF-8'}/views/img/seemore/{$module.short_name|escape:'htmlall':'UTF-8'}.png"
                                 alt="Sample Image">
                        </div>
                        <div class="totmodule_text">
                            <h4>{$module.name|escape:'htmlall':'UTF-8'}</h4>
                            <p>
                                {$module.descr|escape:'htmlall':'UTF-8'}
                            </p>
                        </div>
                        <div class="totmodule_button">
                            {if $module.installed}
                                <a href="{$module.link|escape:'htmlall':'UTF-8'}" class="button configure" role="button"
                                   target="_blank">
                                    {l s='Configuring' mod='totcustomfields'}</a>
                            {else}
                                <a href="{$module.link|escape:'htmlall':'UTF-8'}" class="button discover" role="button"
                                   target="_blank">
                                    {l s='Discover on Addons' mod='totcustomfields'}</a>
                            {/if}
                        </div>
                    </div>
                </fieldset>
            </div>
        {/foreach}
    </div>

</ps-panel>