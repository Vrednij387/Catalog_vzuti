{**
* 2016 TerraNet
*
* NOTICE OF LICENSE
*
* @author    TerraNet
* @copyright 2016 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

<script>
    $(document).ready(function () {
        $('#menuOrderUp').click(function (e) {
            e.preventDefault();
            move(true);
        });
        $('#menuOrderDown').click(function (e) {
            e.preventDefault();
            move();
        });
        $("#items").closest('form').on('submit', function (e) {
            $("#items option").prop('selected', true);
        });
        $("#addItem").click(add);
        $("#availableItems").dblclick(add);
        $("#removeItem").click(remove);
        $("#items").dblclick(remove);
        function add() {
            $("#availableItems option:selected").each(function (i) {
                var val = $(this).val();
                var text = $(this).text();
                text = text.replace(/(^\s*)|(\s*$)/gi, "");
                $("#items").append('<option value="' + val + '" selected="selected">' + text + '</option>');
                $(this).remove();
            });
            serialize();
            return false;
        }

        function remove() {
            $("#items option:selected").each(function (i) {
                var val = $(this).val();
                var text = $(this).text();
                $("#availableItems").append('<option value="' + val + '">' + text + '</option>');
                $(this).remove();
            });
            serialize();
            return false;
        }

        function serialize() {
            var options = "";
            $("#items option").each(function (i) {
                options += $(this).val() + ",";
            });
            $("#itemsInput").val(options.substr(0, options.length - 1));
        }

        function move(up) {
            var tomove = $('#items option:selected');
            if (tomove.length > 1) {
                alert('{l s='Please select just one item' mod='fullfeaturesgroups'}');
                return false;
            }
            if (up)
                tomove.prev().insertAfter(tomove);
            else
                tomove.next().insertBefore(tomove);
            serialize();
            return false;
        }
    });
</script>
<style>
    .itemlabel {
        padding-left: 5px;
    }

    .list_category {
        border: 1px solid #ddd;
        height: 400px;
        overflow-y: scroll;
        display: inline-block;
        padding: 5px;
    }

    .list_category ul {
        list-style-type: none;
        padding-left: 20px;
    }

    .list_category > ul {
        padding-left: 0;
    }

    .list_category ul li label:hover {
        cursor: pointer;
        color: #000000;
    }
</style>
<form action="{$form_action|escape:'htmlall':'UTF-8'}" method="post">
    <div id="product-tab-content-Associations" class="product-tab-content" style="">
        <input type="hidden" name="addcategoryfeaturegroupval" value="1">

        <div id="product-associations" class="panel product-tab">
            <h3>{l s='Add category feature' mod='fullfeaturesgroups'}</h3>

            <div class="row">
                <div class="col-lg-4 col-sm-6 col-md-4">
                    <h4 style="margin-top:5px;">{l s='Selected category' mod='fullfeaturesgroups'}</h4>

                    <div class="list_category">
                        {$category_menu|escape:'quotes':'UTF-8'}
                    </div>
                </div>
                <div class="col-lg-8 col-sm-12 col-md-8">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <h4 style="margin-top:5px;">{l s='Selected items' mod='fullfeaturesgroups'}</h4>
                            <select multiple="multiple" name="items[]" id="items" style="width: 300px; height: 160px;">
                                {foreach from=$selected_feature item=selected_link}
                                    <option value="{$selected_link['value']|intval}">{$selected_link['name']|escape:'htmlall':'UTF-8'} ({$selected_link['description']|escape:'htmlall':'UTF-8'})</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <h4 style="margin-top:5px;">{l s='Available items' mod='fullfeaturesgroups'}</h4>
                            <select multiple="multiple" id="availableItems" style="width: 300px; height: 160px;">
                                {foreach from=$select_feature item=select_link}
                                    <option value="{$select_link['value']|intval}">{$select_link['name']|escape:'htmlall':'UTF-8'} ({$select_link['description']|escape:'htmlall':'UTF-8'})</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <br/>

                    <div class="row">
                        <div class="col-lg-4 col-sm-6"><a href="#" id="removeItem" class="btn btn-default"><i
                                        class="icon-arrow-right"></i> {l s='Remove' mod='fullfeaturesgroups'}</a></div>
                        <div class="col-lg-4 col-sm-6"><a href="#" id="addItem" class="btn btn-default"><i
                                        class="icon-arrow-left"></i> {l s='Add' mod='fullfeaturesgroups'}</a></div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <a href="{$back_action|escape:'htmlall':'UTF-8'}" class="btn btn-default"><i
                            class="process-icon-cancel"></i> {l s='Cancel' mod='fullfeaturesgroups'}</a>
                <button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i
                            class="process-icon-save"></i>{l s='Created' mod='fullfeaturesgroups'}</button>
            </div>
        </div>
</form>