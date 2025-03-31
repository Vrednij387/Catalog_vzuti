{**
 * Copyright 2023 ModuleFactory
 *
 * @author    ModuleFactory
 * @copyright ModuleFactory all rights reserved.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *}

{l s='Available keywords:' mod='fsmetagenerator'}
<a href="javascript:;" onclick="FSMG.toggleDescription('desc_{$fsmg_input_id|escape:'htmlall':'UTF-8'}')">
    {l s='Show/Hide' mod='fsmetagenerator'}
</a>
<div id="desc_{$fsmg_input_id|escape:'htmlall':'UTF-8'}" style="display:none;">
    {l s='To add a keyword click on it!' mod='fsmetagenerator'}<br />
    {foreach from=$fsmg_keywords item=fsmg_keyword name=fsmg_keywords_loop}
        <a href="javascript:;" onclick="{$fsmg_js_function|escape:'htmlall':'UTF-8'}('{$fsmg_keyword|escape:'htmlall':'UTF-8'}', '{$fsmg_input_id|escape:'htmlall':'UTF-8'}')">
            {literal}{{/literal}{$fsmg_keyword|escape:'htmlall':'UTF-8'}{literal}}{/literal}
        </a>
        {if !$smarty.foreach.fsmg_keywords_loop.last}, {/if}
    {/foreach}
</div>
