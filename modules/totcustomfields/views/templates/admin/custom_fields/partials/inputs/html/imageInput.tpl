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

{if version_compare($version, '1.7.6', '>=') && !(isset($isOldPage) && $isOldPage)}
    <div class="form-group row">
        <label class="form-control-label col-lg-3" for="{$code|escape:'htmlall':'UTF-8'}">
            {if $required}
                <span class="text-danger">*</span>
            {/if}
            {$name|escape:'htmlall':'UTF-8'}
            {if !empty($instructions)}
                <span class="help-box"
                      data-toggle="popover"
                      data-content="{$instructions|escape:'htmlall':'UTF-8'}"
                      data-original-title=""
                      title="">
            </span>
            {/if}
        </label>

        <div class="col-sm">

            {if !$translatable}
                <div class="d-flex flex-nowrap m-0 row">
                    <div class="w-100">
                        <img
                                id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_preview"
                                class="{if empty($img_link)}d-none{/if} figure-img img-fluid img-thumbnail"
                                src="{if !empty($img_link)}{$img_link|escape:'htmlall':'UTF-8'}{/if}"
                                style="max-height: 50px; max-width: 200px"/>

                        <input type="file"
                               id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}"
                               name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}]"
                               class="d-none totcustomfields-image-file-{$id|escape:'htmlall':'UTF-8'}"/>
                        {if $hasRealValue}
                            <div class="checkbox d-none">
                                <div class="md-checkbox md-checkbox-inline">
                                    <label>
                                        <input type="checkbox"
                                               id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_delete"
                                               name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][delete]"
                                               class="hide totcustomfields-image-delete-{$id|escape:'htmlall':'UTF-8'}"/>
                                        <i class="md-checkbox-control"></i>
                                    </label>
                                </div>
                            </div>
                        {/if}

                        <div class="custom-file dummyfile input-group">
                            <span class="input-group-addon"><i class="icon-file"></i></span>
                            <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_name"
                                   class="totcustomfields-image-name-{$id|escape:'htmlall':'UTF-8'} custom-file-input"
                                   name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][filename]"
                                   readonly=""
                                   value="{if !empty($value)}{$value|escape:'htmlall':'UTF-8'}{/if}"
                                   type="text"/>
                            <label class="custom-file-label"
                                   for="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][filename]">
                                {l s='Add a file' mod='totcustomfields'}
                            </label>
                        </div>
                        <div
                                id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_errors"
                                style="color: red"
                        ></div>

                    </div>

                    {if $hasRealValue}
                        <button id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_deletebutton"
                                type="button"
                                class="align-self-end btn btn-outline-secondary ml-2 totcustomfields-image-deletebutton-{$id|escape:'htmlall':'UTF-8'}">
                            <i class="material-icons">delete</i>
                        </button>
                    {/if}
                </div>
            {else}
                {foreach from=$languages item=language}
                    <div class="translatable-field row lang-{$language.id_lang|escape:'htmlall':'UTF-8'} locale-input-group flex-nowrap m-0"
                         {if $language.id_lang != $defaultLang}style="display:none"{/if}>
                        <div class="flex-fill">

                            <img
                                    id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_preview"
                                    class="{if empty($img_link[$language.id_lang])}d-none{/if} figure-img img-fluid img-thumbnail"
                                    src="{if !empty($img_link[$language.id_lang])}{$img_link[$language.id_lang]|escape:'htmlall':'UTF-8'}{/if}"
                                    style="max-height: 50px; max-width: 200px"/>

                            <input type="file"
                                   id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}"
                                   name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$language.id_lang|escape:'htmlall':'UTF-8'}]"
                                   class="d-none totcustomfields-image-file-{$id|escape:'htmlall':'UTF-8'}"/>
                            {if $hasRealValue[$language.id_lang]}
                                <div class="checkbox d-none">
                                    <div class="md-checkbox md-checkbox-inline">
                                        <label>
                                            <input type="checkbox"
                                                   id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_delete"
                                                   name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$language.id_lang|escape:'htmlall':'UTF-8'}][delete]"
                                                   class="d-none totcustomfields-image-delete-{$id|escape:'htmlall':'UTF-8'}"/>
                                            <i class="md-checkbox-control"></i>
                                        </label>
                                    </div>
                                </div>
                            {/if}

                            <div class="custom-file dummyfile input-group">
                                <span class="input-group-addon"><i class="icon-file"></i></span>
                                <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_name"
                                       class="custom-file-input totcustomfields-image-name-{$id|escape:'htmlall':'UTF-8'}"
                                       name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$language.id_lang|escape:'htmlall':'UTF-8'}][filename]"
                                       readonly=""
                                       value="{if !empty($value[$language.id_lang])}{$value[$language.id_lang]|escape:'htmlall':'UTF-8'}{/if}"
                                       type="text"/>
                                <label class="custom-file-label"
                                       for="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$language.id_lang|escape:'htmlall':'UTF-8'}][filename]">
                                    {l s='Add a file' mod='totcustomfields'}
                                </label>
                            </div>

                            <div
                                    id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_errors"
                                    style="color: red"
                            ></div>

                        </div>

                        {if $hasRealValue[$language.id_lang]}
                            <button id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_deletebutton"
                                    type="button"
                                    class="align-self-end btn btn-outline-secondary ml-2 totcustomfields-image-deletebutton-{$id|escape:'htmlall':'UTF-8'}">
                                <i class="material-icons">delete</i>
                            </button>
                        {/if}

                        <div class="d-flex align-items-end">
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
            {/if}
        </div>

    </div>
{else}
    <div class="form-group">
        <label class="control-label col-lg-3 {if $required}required{/if}" for="{$code|escape:'htmlall':'UTF-8'}">
            {if $instructions}
            <span class="label-tooltip" data-toggle="tooltip" title=""
                  data-original-title="{$instructions|escape:'htmlall':'UTF-8'}">
        {/if}
                {$name|escape:'htmlall':'UTF-8'}
                {if $instructions}
            </span>
            {/if}
        </label>

        <div class="col-lg-9">

            {if !$translatable}
                <div class="row">
                    <div class="col-lg-9">

                        <input type="file"
                               id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}"
                               name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}]"
                               class="hide totcustomfields-image-file-{$id|escape:'htmlall':'UTF-8'}"/>
                        {if $hasRealValue}
                            <input type="checkbox"
                                   id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_delete"
                                   name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][delete]"
                                   class="hide totcustomfields-image-delete-{$id|escape:'htmlall':'UTF-8'}"/>
                        {/if}

                        <div class="dummyfile input-group">
                            <span class="input-group-addon"><i class="icon-file"></i></span>
                            <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_name"
                                   class="totcustomfields-image-name-{$id|escape:'htmlall':'UTF-8'}"
                                   name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][filename]"
                                   readonly=""
                                   value="{if !empty($value)}{$value|escape:'htmlall':'UTF-8'}{/if}"
                                   type="text"/>
                            <span class="input-group-btn">
                        <button id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_selectbutton"
                                type="button"
                                class="btn btn-primary totcustomfields-image-selectbutton-{$id|escape:'htmlall':'UTF-8'}">
                            <i class="icon-folder-open"></i> {l s='Add a file' mod='totcustomfields'}
                        </button>
                        {if $hasRealValue}
                            <button id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_deletebutton"
                                    type="button"
                                    class="btn btn-default totcustomfields-image-deletebutton-{$id|escape:'htmlall':'UTF-8'}">
                            <i class="icon-trash"></i>
                                {l s='Delete' mod='totcustomfields'}
                        </button>
                        {/if}
                    </span>
                        </div>
                        <div
                                id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_errors"
                                style="color: red"
                        ></div>
                        <img
                                id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_preview"
                                class="{if empty($img_link)}hide{/if}"
                                src="{if !empty($img_link)}{$img_link|escape:'htmlall':'UTF-8'}{/if}"
                                style="max-height: 50px; max-width: 200px"/>

                    </div>
                </div>
            {else}

                {foreach from=$languages item=language}
                    <div class="translatable-field row lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"
                         {if $language.id_lang != $defaultLang}style="display:none"{/if}>

                        <div class="col-lg-9">

                            <input type="file"
                                   id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}"
                                   name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$language.id_lang|escape:'htmlall':'UTF-8'}]"
                                   class="hide totcustomfields-image-file-{$id|escape:'htmlall':'UTF-8'}"/>
                            {if $hasRealValue[$language.id_lang]}
                                <input type="checkbox"
                                       id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_delete"
                                       name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$language.id_lang|escape:'htmlall':'UTF-8'}][delete]"
                                       class="hide totcustomfields-image-delete-{$id|escape:'htmlall':'UTF-8'}"/>
                            {/if}

                            <div class="dummyfile input-group">
                                <span class="input-group-addon"><i class="icon-file"></i></span>
                                <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_name"
                                       class="totcustomfields-image-name-{$id|escape:'htmlall':'UTF-8'}"
                                       name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$language.id_lang|escape:'htmlall':'UTF-8'}][filename]"
                                       readonly=""
                                       value="{if !empty($value[$language.id_lang])}{$value[$language.id_lang]|escape:'htmlall':'UTF-8'}{/if}"
                                       type="text"/>
                                <span class="input-group-btn">
                                <button id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_selectbutton"
                                        type="button"
                                        class="btn btn-default totcustomfields-image-selectbutton-{$id|escape:'htmlall':'UTF-8'}">
                                    <i class="icon-folder-open"></i> {l s='Add a file' mod='totcustomfields'}
                                </button>
                                {if $hasRealValue[$language.id_lang]}
                                    <button id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_deletebutton"
                                            type="button"
                                            class="btn btn-default totcustomfields-image-deletebutton-{$id|escape:'htmlall':'UTF-8'}">
                                    <i class="icon-trash"></i>
                                        {l s='Delete' mod='totcustomfields'}
                                </button>
                                {/if}
                            </span>
                            </div>

                            <div
                                    id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_errors"
                                    style="color: red"
                            ></div>

                            <img
                                    id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}_preview"
                                    class="{if empty($img_link[$language.id_lang])}hide{/if}"
                                    src="{if !empty($img_link[$language.id_lang])}{$img_link[$language.id_lang]|escape:'htmlall':'UTF-8'}{/if}"
                                    style="max-height: 50px; max-width: 200px"/>

                        </div>

                        <div class="col-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                {$language.iso_code|escape:'htmlall':'UTF-8'}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=language}
                                    <li>
                                        <a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});">{$language.name|escape:'htmlall':'UTF-8'}</a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>

                    </div>
                {/foreach}

            {/if}
        </div>

    </div>
{/if}

<script type="text/javascript">

    jQuery(document).ready(function () {

        var authorizedExtensions = [];
        {foreach from=$authorized_extensions item=ext}
        authorizedExtensions.push("{$ext|escape:'javascript':'UTF-8'}");
        {/foreach}

        jQuery(".totcustomfields-image-selectbutton-{$id|escape:'htmlall':'UTF-8'}").click(function (e) {
            var id = this.id.replace("_selectbutton", "");
            jQuery("#" + id).trigger("click");
        });

        jQuery(".totcustomfields-image-name-{$id|escape:'htmlall':'UTF-8'}").click(function (e) {
            var id = this.id.replace("_name", "");
            jQuery("#" + id).trigger("click");
        }).on("dragenter", function (e) {
            e.stopPropagation();
            e.preventDefault();
        }).on("dragover", function (e) {
            e.stopPropagation();
            e.preventDefault();
        });

        jQuery(".totcustomfields-image-file-{$id|escape:'htmlall':'UTF-8'}").change(function (e) {
            var id_name = this.id + "_name";
            var id_errors = this.id + "_errors";
            var id_preview = this.id + "_preview";

            var hasErrors = false;
            if (this.files !== undefined) {
                var files = this.files;
                var name = "";

                jQuery.each(files, function (index, value) {

                    // Check extensions
                    var validExtension = false;
                    for (var i = 0; i < authorizedExtensions.length; ++i) {
                        if (value.name.endsWith("." + authorizedExtensions[i])) {
                            validExtension = true;
                            break;
                        }
                    }

                    if (!validExtension) {
                        hasErrors = true;
                        $("#" + id_errors).html($("<div/>").text("{l s='Invalid file extension : ' mod='totcustomfields'}" + /\.(.+)$/.exec(value.name)[1]));
                        return false;
                    }

                    name += value.name + ", ";
                });

                if (hasErrors) {
                    jQuery("#" + id_name).val("");
                } else {
                    jQuery("#" + id_name).val(name.slice(0, -2));
                    $("#" + id_errors).empty();
                }
                $("#" + id_preview).attr("src", "").addClass("d-none").addClass("hide");
            } else // Internet Explorer 9 Compatibility
            {
                var name = jQuery(this).val().split(/[\\/]/);

                var hasErrors = false;
                for (var i = 0; i < name.length; ++i) {
                    // Check extensions
                    var validExtension = false;
                    for (var i = 0; i < authorizedExtensions.length; ++i) {
                        if (value.name.endsWith("." + authorizedExtensions[i])) {
                            validExtension = true;
                            break;
                        }
                    }

                    if (!validExtension) {
                        hasErrors = true;
                        $("#" + id_errors).html($("<div/>").text("Invalid file extension : " + /\.(.+)$/.exec(value.name)[1]));
                    }
                }

                if (hasErrors) {
                    jQuery("#" + id_name).val("");
                } else {
                    jQuery("#" + id_name).val(name.slice(0, -2));
                    $("#" + id_errors).empty();
                    $("#" + id_preview).attr("src", "").addClass("d-none").addClass("hide");
                }

                if (hasErrors) {
                    jQuery("#" + id_name).val("");
                } else {
                    jQuery("#" + id_name).val(name[name.length - 1]);
                    $("#" + id_errors).empty();
                }
                $("#" + id_preview).attr("src", "").addClass("d-none").addClass("hide");
            }
        });

        jQuery(".totcustomfields-image-deletebutton-{$id|escape:'htmlall':'UTF-8'}").click(function () {
            var id = this.id.replace("_deletebutton", "");
            jQuery("#" + id).val("").change();
            jQuery("#" + id + "_delete").prop("checked", true);
        });
    });

</script>