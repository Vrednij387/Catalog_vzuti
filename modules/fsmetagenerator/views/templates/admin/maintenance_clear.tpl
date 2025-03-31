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

<div id="fsmg_maintenance_clear" class="panel">
    <div class="panel-heading">
        <span>{l s='Clear Manually Written Meta Tags' mod='fsmetagenerator'}</span>
    </div>
    <div class="form-wrapper clearfix">
        <div class="form-group clearfix">
            {assign var="fsmetagenerator_field_name" value="fsmg_maintenance_clear_id_lang"}
            <label class="control-label col-lg-2" for="{$fsmetagenerator_field_name|escape:'htmlall':'UTF-8'}">
                {l s='Content Language:' mod='fsmetagenerator'}
            </label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="col-md-3" id="{$fsmetagenerator_field_name|escape:'htmlall':'UTF-8'}" name="{$fsmetagenerator_field_name|escape:'htmlall':'UTF-8'}">
                        <option value="">{l s='Select Language' mod='fsmetagenerator'}</option>
                        {foreach from=$content_langs item=content_lang}
                            <option value="{$content_lang.id_lang|escape:'htmlall':'UTF-8'}">{$content_lang.name|escape:'htmlall':'UTF-8'}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group clearfix">
            {assign var="fsmetagenerator_field_name" value="fsmg_maintenance_clear_type"}
            <label class="control-label col-lg-2" for="{$fsmetagenerator_field_name|escape:'htmlall':'UTF-8'}">
                {l s='Content Type:' mod='fsmetagenerator'}
            </label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="col-md-3" id="{$fsmetagenerator_field_name|escape:'htmlall':'UTF-8'}" name="{$fsmetagenerator_field_name|escape:'htmlall':'UTF-8'}">
                        <option value="all">{l s='All Content Type' mod='fsmetagenerator'}</option>
                        {foreach from=$content_types item=content_type_name key=content_type_value}
                        <option value="{$content_type_value|escape:'htmlall':'UTF-8'}">{$content_type_name|escape:'htmlall':'UTF-8'}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group clearfix">
            {assign var="fsmetagenerator_field_name" value="fsmg_maintenance_clear_field"}
            <label class="control-label col-lg-2" for="{$fsmetagenerator_field_name|escape:'htmlall':'UTF-8'}">
                {l s='Meta Field:' mod='fsmetagenerator'}
            </label>
            <div class="col-lg-10">
                <div class="form-group">
                    <select class="col-md-3" id="{$fsmetagenerator_field_name|escape:'htmlall':'UTF-8'}" name="{$fsmetagenerator_field_name|escape:'htmlall':'UTF-8'}">
                        <option value="all">{l s='All Meta Field' mod='fsmetagenerator'}</option>
                        {foreach from=$meta_fields item=meta_fields_name key=meta_fields_value}
                        <option value="{$meta_fields_value|escape:'htmlall':'UTF-8'}">{$meta_fields_name|escape:'htmlall':'UTF-8'}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <a href="javascript:;" onclick="FSMG.generateClearQueue();" class="btn btn-default"><i class="process-icon-update"></i>{l s='Clear Selected' mod='fsmetagenerator'}</a>
    </div>
</div>
<div id="fsmg_maintenance_clear_queue_content" class="clearfix"></div>