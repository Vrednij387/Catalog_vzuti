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

<ps-input-text name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][default_value]"
               label="{l s='Default value' mod='totcustomfields'}"
               size="255"
               hint="{l s='The input will be filled with this value if no data is present.' mod='totcustomfields'}"
               value="{if isset($data.currentConf)}{$data.currentConf.default_value|escape:'htmlall':'UTF-8'}{/if}"
               fixed-width="lg"></ps-input-text>

<ps-switch name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][translatable]"
           label="{l s='Translatable' mod='totcustomfields'}"
           yes="{l s='Yes' mod='totcustomfields'}" no="{l s='No' mod='totcustomfields'}"
           hint="{l s='Is this input multilang ?' mod='totcustomfields'}"
           active="{if isset($data.currentConf) && !$data.currentConf.translatable}false{else}true{/if}"></ps-switch>