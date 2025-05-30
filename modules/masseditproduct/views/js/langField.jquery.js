/**
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
 */

$(function () {
    $.id_current_lang = null;

    $.changeLanguage = function(id_lang) {
        $('.translatable-field').hide();
        $('.translatable-field.lang-'+id_lang).show();
        $.setCurrentLang(id_lang);
    };

    $.setCurrentLang = function (id_lang) {
        $.id_current_lang = id_lang;
    };

    $.getCurrentLang = function () {
        return $.id_current_lang;
    };

    $.triggerChangeLang = function () {
        $.changeLanguage($.getCurrentLang());
    };
});