{**
* 2016 TerraNet
* NOTICE OF LICENSE
* @author    TerraNet
* @copyright 2016 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{if !empty($data_feature)}
  <section class="product-features tabs card">
    <div class="{$column_class_prefix|escape:'htmlall':'UTF-8'}-feature-column ffg-style-container">
        {foreach from=$data_feature item=feature}
          <div class="table-feature-container">
              {if count($feature['items']) > 0}
                  <div>
                      <span>{l s='Категорія'}</span>
                      <span class="value"><a href="/{$category->id}-{$category->link_rewrite}" title="{$category->name}">{$category->name}</a>
                      </span>
                  </div>
				  {if $collection_name && $collection_link}
				  <div>
                      <span>{l s='Колекція'}</span>
                      <span class="value"><a href="{$collection_link}" title="{$collection_name}">{$collection_name}</a>
                      </span>
                  </div>
				  {/if}
                  <div>
                      <span>{l s='Бренд'}</span>
                      <span class="value">
                          <a href="/brand/{$product_manufacturer->id}-{$product_manufacturer->link_rewrite}" title="Взуття від {$product_manufacturer->name}">{$product_manufacturer->name}</a>
                      </span>
                  </div>
                  {foreach from=$feature['items'] item=feature_list}
                    <div class="{cycle values="odd,even"}">
                      <span>{$feature_list['name_feature']|escape:'htmlall':'UTF-8'}</span>
                      <span class="value">
                          {if isset($feature_list['value']) && is_array($feature_list['value'])}
                              {assign var="feature_loop" value=count($feature_list['value'])}
                              {foreach from=$feature_list['value'] item=feature_val}
                                  {$feature_loop = $feature_loop-1}
                                  {$feature_val|escape:'htmlall':'UTF-8'}{if $feature_loop > 0}{if isset($new_line_properties) && $new_line_properties}

                              {else},{/if}{/if}
                              {/foreach}
                          {else}
                              {$feature_list['value']|escape:'htmlall':'UTF-8'}
                          {/if}
                      </span>
                    </div>
                  {/foreach}
              {/if}

          </div>
        {/foreach}
    </div>
  </section>
{/if}