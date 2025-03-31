
<div class="products-sort-nb-dropdown products-sort-order dropdown">
    <a class="select-title expand-more form-control" rel="nofollow" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" aria-label="{l s='Sort by selection' d='Shop.Theme.Global'}">
       <span class="select-title-name">
           {*if $listing.sort_selected}
               {$listing.sort_selected}
           {else}
               {l s='Choose' d='Shop.Theme.Actions'}

               {/if*}
           {$listing.sort_selected}
       </span>

        {*<i class="fa fa-angle-down" aria-hidden="true"></i>*}
    </a>
    <div class="dropdown-menu">
        {foreach from=$listing.sort_orders item=sort_order}
            {if $sort_order.current}
                {assign var="currentSortUrl" value=$sort_order.url|regex_replace:"/&resultsPerPage=\d+$/":""}
            {/if}
            <a
                    rel="nofollow"
                    href="{$sort_order.url}"
                    class="select-list dropdown-item {['current' => $sort_order.current, 'js-search-link' => true]|classnames}"
            >
                {$sort_order.label}
            </a>
        {/foreach}
    </div>
</div>
