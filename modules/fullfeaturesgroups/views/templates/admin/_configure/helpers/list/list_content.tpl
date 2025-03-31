{**
* 2018 TerraNet
*
* NOTICE OF LICENSE
*
* @author    TerraNet
* @copyright 2018 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{extends file="helpers/list/list_content.tpl"}

{block name="td_content"}
	{if isset($params.type) && $params.type == 'text_html'}
		{$tr.$key|escape:'html':'UTF-8'|unescape:"html" nofilter}
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
