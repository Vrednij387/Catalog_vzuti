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

<input type="checkbox" name="multishop_override_enabled[]" value="{$params.name|escape:'htmlall':'UTF-8'}"
       id="conf_helper_{$params.name|escape:'htmlall':'UTF-8'}" {if !$params.is_disabled} checked="checked"{/if}
       onclick="FSMG.toggleMultishopDefaultValue($(this), '{$params.name|escape:'htmlall':'UTF-8'}')">
<input type="hidden" name="multishop_override_fields[]" value="{$params.name|escape:'htmlall':'UTF-8'}">
<script>
    $(document).ready(function(){
        FSMG.toggleMultishopDefaultValue($('#conf_helper_{$params.name|escape:'htmlall':'UTF-8'}'), '{$params.name|escape:'htmlall':'UTF-8'}');
    });
</script>