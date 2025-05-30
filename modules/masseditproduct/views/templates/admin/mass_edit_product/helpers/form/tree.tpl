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
*  @author    SeoSA <885588@bk.ru>
*  @copyright 2012-2023 SeoSA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script>
    var only_default_desc = "{l s='only_default_desc' mod='masseditproduct'}";
    var only_explicit_desc = "{l s='only_explicit_desc' mod='masseditproduct'}";
    var includingsubcategories = "{l s='including_subcategories' mod='masseditproduct'}";
</script>

{if isset($view_header) && $view_header && isset($root) && $root}
	<span class="tree_categories_header margin-right float-left">

		<div class="float-left w-100">
			<div class="float-left form-group">
				<a class="collapse_all btn btn-default button margin-right" href="#" style="display: none;">
					<i class="icon-collapse-alt"></i>
                    {l s='Collapse all' mod='masseditproduct'}
				</a>
			</div>
			<div class="float-left form-group">
				<a class="expand_all btn btn-default button margin-right" href="#">
					<i class="icon-expand-alt"></i>
                    {l s='Expand all' mod='masseditproduct'}
				</a>
			</div>
			<div class="float-left form-group">
				<a class="check_all_associated_categories btn btn-default button margin-right" href="#">
					<i class="icon-check-sign"></i>
                    {l s='Check all' mod='masseditproduct'}
				</a>
			</div>
			<div class="float-left form-group">
				<a class="uncheck_all_associated_categories btn btn-default button margin-right" href="#" style="display: none;">
					<i class="icon-check-empty"></i>
                    {l s='Uncheck all' mod='masseditproduct'}
				</a>
			</div>

			<div class="float-left w-xs-100">
				<span class="wrapp_search_category margin-right fixed-width-xxl w-xs-100">
					<input type="text" class="search_category form-control">
				</span>
			</div>
		</div>

		<div class="float-left w-100">
            {if isset($view_header) && $view_header && isset($root) && $root}
                {if isset($search_view)}

					<span class="wrap_snap_category2 inline-block">
						<div class="">
							<div class="md-checkbox">
								<label class="control-label">
									<input type="checkbox" id="bind_child2" />
									<i class="md-checkbox-control"></i>
                                    {l s='Including subcategories' mod='masseditproduct'}
									<span onclick="including()" class="help-box"></span>
								</label>
							</div>
						</div>
						<div class="">
							<div class="md-checkbox">
								<label class="control-label">
									<input type="checkbox" id="search_only_default_category" />
									<i class="md-checkbox-control"></i>
                                    {l s='Only default' mod='masseditproduct'}
									<span onclick="onlyDefault()" class="help-box"></span>
								</label>
							</div>
						</div>
						<div>
							<div class="md-checkbox">
								<label class="control-label">
									<input type="checkbox" id="search_only_explicit_category" />
									<i class="md-checkbox-control"></i>
                                    {l s='Only explicit' mod='masseditproduct'}
									<span onclick="onlyExplicit()" class="help-box"></span>
								</label>
							</div>
						</div>
					</span>

                {else}
					<div class="wrap_snap_category1 margin-right">
						<div class="md-checkbox">
							<label class="control-label">
								<input type="checkbox" id="bind_child" />
								<i class="md-checkbox-control"></i>
                                {l s='Snap the child categories' mod='masseditproduct'}
							</label>
						</div>
					</div>
                {/if}
            {/if}
		</div>

	</span>
{/if}



{if isset($categories)}
	<ul {if isset($root) && $root}class="float-left w-100 {if isset($class_tree)}{$class_tree|escape:'quotes':'UTF-8'}{else}tree_categories{/if} tree_root"{/if}>
        {if isset($categories[$id_category])}
            {foreach from=$categories[$id_category] item=category}
                <li class="tree_item {if !$category['infos']['active']}tree_no_active{/if}">
                    <span class="tree_label {if isset($selected_categories) && in_array($category['infos']['id_category'], $selected_categories)}tree_selected{/if}">

						<span class="md-checkbox">
							<label>
								<input data-name="{$category['infos']['name']|escape:'htmlall':'UTF-8'}" {if isset($selected_categories) && in_array($category['infos']['id_category'], $selected_categories)}checked{/if} class="tree_input"
									   type="{if isset($multiple) && $multiple}checkbox{else}radio{/if}"
									   name="{$name|escape:'quotes':'UTF-8'}[]"
									   value="{$category['infos']['id_category']|escape:'quotes':'UTF-8'}"
									   data-search="{$category['infos']['name']|lower|no_escape}" />
								<i class="md-checkbox-control"></i>
							</label>
						</span>

                        <label class="tree_toogle">
                            {if isset($categories[$category['infos']['id_category']]) && count($categories[$category['infos']['id_category']])}
                                <i class="icon-folder-close"></i>
                            {else}
                                <i class="tree-dot"></i>
                            {/if}
                            {$category['infos']['name']|escape:'htmlall':'UTF-8'}
                            {if isset($categories[$category['infos']['id_category']]) && count($categories[$category['infos']['id_category']])}
                                <span class="tree_counter"></span>
                            {/if}
                        </label>
                    </span>
                    {if isset($categories[$category['infos']['id_category']]) && count($categories[$category['infos']['id_category']])}
                        {include file="./tree.tpl" categories=$categories id_category=$category['infos']['id_category'] selected_categories=$selected_categories root=false}
                    {/if}
                </li>
            {/foreach}
        {/if}
	</ul>
{elseif $root}
	{l s='Not categories' mod='masseditproduct'}
{/if}
