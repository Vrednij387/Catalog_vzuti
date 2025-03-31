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

{fsmgMinifyCss}
<style type="text/css">
</style>
{/fsmgMinifyCss}

<script type="text/javascript">
    var FSMG = FSMG || { };
    FSMG.requestToken = '{$fsmg_js.request_token|escape:'html':'UTF-8'}';
    FSMG.requestTime = {$fsmg_js.request_time|escape:'html':'UTF-8'};
    FSMG.metaFieldsByType = {$fsmg_js.meta_fields_by_type|escape:'html':'UTF-8'|fsmgCorrectTheMess};
    FSMG.generateMetaUrl = '{$fsmg_js.generate_meta_url|escape:'html':'UTF-8'|fsmgCorrectTheMess}';
    FSMG.generateClearQueueUrl = '{$fsmg_js.generate_clear_queue_url|escape:'html':'UTF-8'|fsmgCorrectTheMess}';
    FSMG.clearMetaFieldUrl = '{$fsmg_js.clear_meta_field_url|escape:'html':'UTF-8'|fsmgCorrectTheMess}';
    FSMG.translateClearComplete = '{l s='Meta tag clearing completed.' mod='fsmetagenerator'}';
    FSMG.translateDone = '{l s='DONE!' mod='fsmetagenerator'}';
    FSMG.translateOk = '{l s='OK' mod='fsmetagenerator'}';
    FSMG.translateCancel = '{l s='Cancel' mod='fsmetagenerator'}';
    FSMG.translateAreYouSure = '{l s='Are you sure?' mod='fsmetagenerator'}';
    FSMG.translateClearIntentText = '{l s='You intend to clear manually written meta tags' mod='fsmetagenerator'}';
    FSMG.translateYesClearIt = '{l s='Yes, clear it!' mod='fsmetagenerator'}';
</script>