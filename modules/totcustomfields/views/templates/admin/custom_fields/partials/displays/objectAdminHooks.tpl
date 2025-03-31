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

<ps-select name="code_admin_display" label="{l s='Back Office display method' mod='totcustomfields'}"
           required-input="false"
           hint="{l s='The choice of display method is not mandatory' mod='totcustomfields'}"
           chosen='true'
           class="asyncMount"
>
  {foreach $hooks as $hook => $hookName}
    <option value="{$hook|escape:'htmlall':'UTF-8'}"
            {if $selectedHook == $hook}selected{/if}>
      {$hookName|escape:'htmlall':'UTF-8'}
    </option>
  {/foreach}
</ps-select>