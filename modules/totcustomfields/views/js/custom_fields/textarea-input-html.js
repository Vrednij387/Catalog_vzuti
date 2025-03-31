/**
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
 */

// PS's function is declared inline on PS1.6.0.X, we'll declare our own
function totcustomfields_countDown($source, $target) {
    var max = $source.attr("data-maxchar");
    $target.html(max - $source.val().length);

    $source.keyup(function () {
        $target.html(max - $source.val().length);
    });
}