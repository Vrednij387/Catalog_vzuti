    {foreach from=$variants item=variant name=size key=i}
        {if $variants|@count > 5}
            {if $smarty.foreach.size.iteration < 4 OR $smarty.foreach.size.last }
                <span class="{$i}">{$variant.name}</span>
            {elseif $smarty.foreach.size.iteration == 4}
                <span class="{$i}">...</span>
            {/if}
        {else}
            <span class="{$i}">{$variant.name}</span>
        {/if}
    {/foreach}

