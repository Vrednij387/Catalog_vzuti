{**
 * Copyright since 2022 totcustomfields
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to tech@202-ecommerce.com so we can send you a copy immediately.
 *
 * @author    202 ecommerce <tech@202-ecommerce.com>
 * @copyright 2022 totcustomfields
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 *}

{if (isset($totcustomfields_js_def) && count($totcustomfields_js_def) || isset($js_files) && count($js_files))}
    {include file=$smarty.const._PS_ALL_THEMES_DIR_|cat:"javascript.tpl"}
{/if}

<div class="row px-0 mx-0">
    <div class="col-md-9">
        <fieldset class="form-group translationsFields tab-content">
            <label class="form-control-label">
                {if $required}
                    <span style="color: red">*</span>
                {/if}
                {$name|escape:'htmlall':'UTF-8'}
                {if !empty($instructions)}
                    <span class="help-box"
                          data-toggle="popover"
                          data-content="{$instructions|escape:'htmlall':'UTF-8'}"
                          data-original-title=""
                          title=""></span>
                {/if}
            </label>

            {if !$translatable}
                <textarea
                        id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}"
                        name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}]"
                        {if isset($maxlength)}
                            counter="{$maxlength|intval}"
                            counter_type="recommended"
                        {/if}
                        class="form-control {if $format == 'wysiwyg'}totcustomfields_rte{/if}"
                >{if $value}{$value|escape:'htmlall':'UTF-8'}{else}{$default_value|escape:'htmlall':'UTF-8'}{/if}</textarea>
                {if isset($maxlength)}
                    <span class="maxLength">
                        <span class="currentLength"></span>
                        {l s='of' mod='totcustomfields'}
                        <span class="currentTotalMax">{$maxlength|intval}</span> {l s='characters allowed' mod='totcustomfields'}
                    </span>
                {/if}
            {else}
            {foreach from=$languages item=language}
                <div class="translatable-field row lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"
                     {if $language.id_lang != $defaultLang}style="display:none"{/if}>
                    <div class="col-lg-9" style="padding-right: 0;">
                        {if isset($maxlength) && $maxlength && $format != 'wysiwyg'}
                        <div class="d-flex flex-column input-group">
                            {/if}
                            <textarea
                                    id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}"
                                    name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$language.id_lang|escape:'htmlall':'UTF-8'}]"
                                    class="{if $format == 'wysiwyg'}totcustomfields_rte{else}textarea-autosize-{$id|escape:'htmlall':'UTF-8'} form-control{/if} w-100"
                                    {if isset($maxlength) && $maxlength}
                                maxlength="{$maxlength|intval}"
                                counter="{$maxlength|intval}"
                                data-maxlength="{$maxlength|intval}"
                                data-maxchar="{$maxlength|intval}"
                                    {/if}
                                style="border: 1px solid rgb(187, 205, 210);"
                            >{if isset($value[$language.id_lang])}{$value[$language.id_lang]|escape:'htmlall':'UTF-8'}{else}{$default_value|escape:'htmlall':'UTF-8'}{/if}</textarea>
                            {if isset($maxlength) && $maxlength && $format != 'wysiwyg'}
                                <small id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_counter"
                                       class="input-group-addon form-text text-right text-muted">
                                    <em class="text-count-down">{$maxlength|intval}</em>
                                </small>
                            {/if}
                            <span class="maxLength">
                                <span class="currentLength"></span>
                                {l s='of' mod='totcustomfields'}
                                <span class="currentTotalMax">{$maxlength|intval}</span> {l s='characters allowed' mod='totcustomfields'}
                            </span>
                            {if isset($maxlength) && $maxlength && $format != 'wysiwyg'}
                        </div>
                        {/if}
                    </div>
                    <div class="col-lg-2">
                        <div class="dropdown">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle js-locale-btn"
                                    data-toggle="dropdown">
                                {$language.iso_code|escape:'htmlall':'UTF-8'}
                                <span class="caret"></span>
                            </button>
                            <div class="dropdown-menu">
                                {foreach from=$languages item=lang}
                                    <a class="dropdown-item"
                                       href="javascript:hideOtherLanguage({$lang.id_lang|escape:'htmlall':'UTF-8'});">
                                        {$lang.name|escape:'htmlall':'UTF-8'}
                                    </a>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}
            {if isset($maxlength) && $maxlength && $format != 'wysiwyg'}
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        {foreach from=$languages item=language}
                        totcustomfields_countDown(jQuery("#totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}"), jQuery("#totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_counter .text-count-down"));
                        {/foreach}
                    });
                </script>
            {/if}
            {/if}

        </fieldset>
    </div>
</div>

<script>
    $(document).ready(function () {
        {if $format == 'wysiwyg'}
        // From js/admin/tinymce_loader.js
        tinySetup({
            editor_selector: "totcustomfields_rte, autoload_rte",
            allow_script_urls: true,
            height: 70,
            resize: true,
            autoresize_min_height: 70,
            setup: function (ed) {
                ed.on("loadContent", function (ed, e) {
                    handleCounterTiny(tinymce.activeEditor.id);
                });
                ed.on("change", function (ed, e) {
                    tinyMCE.triggerSave();
                    handleCounterTiny(tinymce.activeEditor.id);
                });
                ed.on("blur", function (ed) {
                    tinyMCE.triggerSave();
                });
            }
        });

        function handleCounterTiny(id) {
            let textarea = $("#" + id);
            if (!textarea.hasClass("totcustomfields_rte")) {
              return;
            }
            let counter = textarea.attr("counter");
            let counter_type = textarea.attr("counter_type");
            const editor = window.tinyMCE.get(id);
            const max = editor.getBody() ? editor.getBody().textContent.length : 0;

            textarea
                    .parent()
                    .find("span.currentLength")
                    .text(max);
            if ("recommended" !== counter_type && max > counter) {
                textarea
                        .parent()
                        .find("span.maxLength")
                        .addClass("text-danger");
            } else {
                textarea
                        .parent()
                        .find("span.maxLength")
                        .removeClass("text-danger");
            }
        }
        {/if}
    });
</script>