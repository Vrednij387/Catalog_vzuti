
<div class="product-variants js-product-variants">

        <div class="eu_size">
            {l s='Розміри відображені европейські (EU)'}
        </div>


    {foreach from=$groups key=id_attribute_group item=group}
        {if !empty($group.attributes)}
        <div class="clearfix product-variants-item product-variants-item-{$id_attribute_group}">
            {*<span class="form-control-label">{$group.name}</span>*}
            {if $group.group_type == 'select'}
                <div class="custom-select2">
                <select
                        id="group_{$id_attribute_group}"
                        aria-label="{$group.name}"
                        data-product-attribute="{$id_attribute_group}"
                        name="group[{$id_attribute_group}]"
                        class="form-control form-control-select">
                    {foreach from=$group.attributes key=id_attribute item=group_attribute}
                        <option value="{$id_attribute}"
                                title="{$group_attribute.name}"{if $group_attribute.selected} selected="selected"{/if} {if $group.attributes_quantity.$id_attribute <= 0} class="attribute-not-in-stock"{/if}>{$group_attribute.name}

                      </option>
                    {/foreach}
                </select>
                </div>
            {elseif $group.group_type == 'color'}
                <ul id="group_{$id_attribute_group}">
                    {foreach from=$group.attributes key=id_attribute item=group_attribute}
                        <li class="float-left input-container {if $group.attributes_quantity.$id_attribute <= 0} attribute-not-in-stock{/if}" data-toggle="tooltip" data-animation="false" data-placement="top"  data-container= ".product-variants" title="{$group_attribute.name}">
                            <input class="input-color" type="radio" data-product-attribute="{$id_attribute_group}"
                                   name="group[{$id_attribute_group}]"
                                   value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                            <span
                                    {if $group_attribute.texture}
                                        class="color texture" style="background-image: url({$group_attribute.texture})"
                                    {elseif $group_attribute.html_color_code}
                                        class="color" style="background-color: {$group_attribute.html_color_code}"
                                    {/if}
                            ><span class="attribute-name sr-only">{$group_attribute.name}</span></span>
                        </li>
                    {/foreach}
                </ul>
            {elseif $group.group_type == 'radio'}
                <ul id="group_{$id_attribute_group}">
					{assign var="id_attribute_selected"  value=0}
					{assign var="name_attribute_selected"  value=""}
                    {foreach from=$group.attributes key=id_attribute item=group_attribute}
                        <li class="input-container float-left {if $group.attributes_quantity.$id_attribute <= 0} attribute-not-in-stock{/if}">
                            <input class="input-radio" type="radio" data-product-attribute="{$id_attribute_group}"
                                   name="group[{$id_attribute_group}]"
                                   title="{$group_attribute.name}"
                                   value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{$id_attribute_selected = $id_attribute}{$name_attribute_selected = $group_attribute.name}{/if}>
                            <span class="radio-label">{$group_attribute.name}</span>
                        </li>
                    {/foreach}
                </ul>
            {/if}
        </div>
			{if $id_attribute_selected && $group.attributes_quantity[$id_attribute_selected] == 1}
				<div class="product_attribute_end"><b>{$name_attribute_selected}</b>{l s=' - залишилась остання пара в наявності'}</div>
			{/if}
        {/if}
    {/foreach}

        <div class="none_size">
            <p class="title">{l s='Не знайшли свій розмір у наявності? '}<span id="none_size_open">{l s='тисни сюди'}</span></p>
            <div class="none_size_info">
                <p class="h2">Напишіть нам щоб уточнити чи можна зробити передзамовлення вашого розміру.</p>
                <div class="contacts">
                   {* <p class="telephone"><a target="_blank" href="tel:+380502492615" title="+380 (50) 249 26 15" rel="noreferrer noopener">+380 (50) 249 26 15</a></p>*}
                    <p class="insta"><a href="https://instagram.com/vzuti_store" title="instagarm" target="_blank" rel="noreferrer noopener">Instagram</a></p>
                    <p class="telegram"><a href="https://telegram.me/vzuti_store" target="_blank" rel="noreferrer noopener">Telegram </a></p>
                    <p class="viber"><a href="viber://chat?number=+380502492615" target="_blank" rel="noreferrer noopener">Viber </a></p>
                </div>
            </div>
        </div>
 
</div>




