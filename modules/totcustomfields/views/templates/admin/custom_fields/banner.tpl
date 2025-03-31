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

<div id="tot_banner_container">
    <table class="banner">
        <tr>
            <td class="module_informations">
                <img src="{$module.folderLink|escape:'htmlall':'UTF-8'}logo.gif">
                <br /><span class="white"><span>{$module.displayName|escape:'htmlall':'UTF-8'}</span></span>
                <p>{$module.description|escape:'htmlall':'UTF-8'}</p>
            </td>

            <td class="links_container">

                <a href="https://addons.prestashop.com/contact-form.php?id_product=29193" target="_blank" title="{l s='Contact us' mod='totcustomfields'}">
                    <img src="{$module.folderLink|escape:'htmlall':'UTF-8'}views/img/banner/question-mark.png" />
                </a>

                <a href="https://addons.prestashop.com/en/ratings.php" target="_blank" title="{l s='Rate our module' mod='totcustomfields'}">
                    <img src="{$module.folderLink|escape:'htmlall':'UTF-8'}views/img/banner/star.png" />
                </a>

                <a href="https://addons.prestashop.com/en/27_202-ecommerce" target="_blank">
                    <img src="{$module.folderLink|escape:'htmlall':'UTF-8'}views/img/banner/logo-202-v2.png" id="logo202" />
                </a>

            </td>
        </tr>
    </table>
</div>