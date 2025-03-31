{if $format == 'text'}
{assign var='escaped_value' value=$value|escape:'htmlall':'UTF-8'}
{$escaped_value|nl2br nofilter}
{else}
{$value nofilter}
{/if}