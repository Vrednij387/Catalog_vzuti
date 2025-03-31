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

<div id="fsmg_queue" class="panel">
    <div class="panel-heading">
        <span>{l s='Process Queue' mod='fsmetagenerator'}</span>
    </div>
    <div class="fsmg_queue_header">
        <div class="col-md-4 col-xs-6">
            {l s='Content Type' mod='fsmetagenerator'}
        </div>
        <div class="col-md-4 col-xs-6">
            {l s='Meta Field' mod='fsmetagenerator'}
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="fsmg_queue_list">
        {foreach from=$meta_fields_by_type item=content_type key=content_type_key}
            {foreach from=$content_type item=meta_field key=meta_field_key}
                <div data-contenttype="{$content_type_key|escape:'htmlall':'UTF-8'}"
                     data-metafield="{$meta_field_key|escape:'htmlall':'UTF-8'}"
                     data-selectedidlang="{$selected_id_lang|escape:'htmlall':'UTF-8'}"
                     id="{$content_type_key|escape:'htmlall':'UTF-8'}_{$meta_field_key|escape:'htmlall':'UTF-8'}" class="fsmg_queue_item clearfix">
                    <div class="col-md-4 col-xs-6 fsrt_col">
                        {$content_types[$content_type_key]|escape:'htmlall':'UTF-8'}
                    </div>
                    <div class="col-md-4 col-xs-6 fsrt_col">
                        {$meta_field|escape:'htmlall':'UTF-8'}
                    </div>
                    <div class="col-md-4 col-xs-12 fsrt_col fsrt_progress_col">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;" id="{$content_type_key|escape:'htmlall':'UTF-8'}_{$meta_field_key|escape:'htmlall':'UTF-8'}_progress_bar">
                                {l s='Queued' mod='fsmetagenerator'}
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}
        {/foreach}
    </div>
</div>