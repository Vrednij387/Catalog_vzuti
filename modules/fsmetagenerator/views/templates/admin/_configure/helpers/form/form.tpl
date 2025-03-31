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

{extends file="helpers/form/form.tpl"}

{block name="legend"}
    {$smarty.block.parent}
    {if isset($field.show_multishop_header) && $field.show_multishop_header}
    <div class="well clearfix">
        <label class="control-label col-lg-3">
            <i class="icon-sitemap"></i> {l s='Multistore' mod='fsmetagenerator'}
        </label>
        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-12">
                    <p class="help-block">
                        <strong>{l s='You are editing this page for a specific shop or group.' mod='fsmetagenerator'}</strong><br />
                        {l s='If you check a field, change its value, and save, the multistore behavior will not apply to this shop (or group), for this particular parameter.' mod='fsmetagenerator'}
                    </p>
                </div>
            </div>
        </div>
    </div>
    {/if}
{/block}