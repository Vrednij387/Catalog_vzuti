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

<div id="fsmg_tabs" class="col-lg-2 col-md-3">
    <div class="list-group">
        {foreach from=$fsmg_tab_layout item=fsmg_tab}
            <a class="list-group-item{if $fsmg_active_tab == $fsmg_tab.id} active{/if}"
               href="#{$fsmg_tab.id|escape:'htmlall':'UTF-8'}"
               aria-controls="{$fsmg_tab.id|escape:'htmlall':'UTF-8'}" role="tab" data-toggle="tab">
                {$fsmg_tab.title|escape:'htmlall':'UTF-8'}
            </a>
        {/foreach}
    </div>
    <div class="fsmg-side-menu-container">
        <div class="fsmg-brand-container">
            <div class="fsmg-brand-col c-1"></div>
            <div class="fsmg-brand-col c-2"></div>
            <div class="fsmg-brand-col c-3"></div>
            <div class="fsmg-brand-col c-4"></div>
        </div>
        <div class="fsmg-need-help-container">
            <i class="fsmg-fa fsmg-fa-question-circle fsmg-need-help-question-mark" aria-hidden="true"></i>
            <a class="fsmg-need-help-link" href="{$fsmg_contact_us_url|escape:'html':'UTF-8'|fsmgCorrectTheMess}" target="_blank">
                Need help? <i class="fsmg-fa fsmg-fa-external-link" aria-hidden="true"></i>
            </a>
        </div>
        <div class="fsmg-more-modules-container">
            <img src="{$fsmg_module_base_url|escape:'html':'UTF-8'}views/img/modules-link-logo-40.jpg">
            <a class="fsmg-more-modules-link" href="https://addons.prestashop.com/en/2_community-developer?contributor=271190" target="_blank">
                Our modules! <i class="fsmg-fa fsmg-fa-external-link" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>
<div class="col-lg-10 col-md-9">
    <div class="tab-content">
        {foreach from=$fsmg_tab_layout item=fsmg_tab}
            <div role="tabpanel" class="tab-pane{if $fsmg_active_tab == $fsmg_tab.id} active{/if}" id="{$fsmg_tab.id|escape:'htmlall':'UTF-8'}">
                {$fsmg_tab.content|escape:'html':'UTF-8'|fsmgCorrectTheMess}
            </div>
        {/foreach}
    </div>
</div>
<div class="clearfix"></div>