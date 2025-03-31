{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    SeoSA <885588@bk.ru>
* @copyright 2012-2023 SeoSA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

<div class="form-group" data-customization-field="{$type|intval}">

    {if $type == 0}
        <label class="control-label margin-right float-left pt-0 pt-sm-1">{l s='Set label file field' mod='masseditproduct'}:</label>
    {/if}
    {if $type == 1}
        <label class="control-label margin-right float-left pt-0 pt-sm-1">{l s='Set label text field' mod='masseditproduct'}:</label>
    {/if}

    {include file="./input_text_lang.tpl" input_name="label_{$type|no_escape}_{$counter|no_escape}_name" languages=$languages}

    <div class="float-left">
        <span class="md-checkbox">
            <label class="control-label">
                <input type="checkbox" value="1" name="label_{$type|no_escape}_{$counter|no_escape}_required">
                <i class="md-checkbox-control"></i>
                {l s='Required' mod='masseditproduct'}
            </label>
        </span>
    </div>

</div>