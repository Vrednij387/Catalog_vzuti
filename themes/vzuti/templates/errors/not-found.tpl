{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{*
<section id="content" class="page-content page-not-found">
  {block name='page_content'}

    {block name="error_content"}
      {if isset($errorContent)}
        {$errorContent nofilter}
      {else}
        <h4>{l s='This page could not be found' d='Shop.Theme.Global'}</h4>
        <p>{l s='Try to search our catalog, you may find what you are looking for!' d='Shop.Theme.Global'}</p>
      {/if}
    {/block}

    {block name='search'}
      {hook h='displaySearch'}
    {/block}

    {block name='hook_not_found'}
      {hook h='displayNotFound'}
    {/block}

  {/block}
</section>
*}
<section id="content" class="page-content page-not-found">
  {block name="error_content"}

      <img src="/themes/vzuti/assets/img/not_found.webp" alt="Сторінку не знайдено" title="Сторінку не знайдено">

    <p>Упс... Сторінку яку ви шукаєте було переміщено, видалено, або ви перейшли за невірним посиланням. Ви легко можете знайти те, що шукали, скориставшись пошуком вище, або перейшовши
      <a href="/" title="на головну сторінку" class="to_home"> на головну сторінку</a></p>
  {/block}
</section>

