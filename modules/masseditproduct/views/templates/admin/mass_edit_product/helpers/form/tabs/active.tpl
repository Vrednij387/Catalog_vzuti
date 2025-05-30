{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    SeoSA <885588@bk.ru>
* @copyright 2012-2023 SeoSA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}


{extends file="../tab_layout.tpl"}

{block name="form"}
    <div class="row enabled_option_stage_static">
        <div class="col-sm-12">
            <label class="control-label margin-right float-left pt-0 pt-sm-1">{l s='Set active for all products' mod='masseditproduct'}:</label>
            <div class="btn-group btn-group-radio float-left">
                <label for="is_active_disable">
                    <input type="radio" checked name="is_active" value="-1" id="is_active_disable"/>
                    <span>{l s='Do nothing' mod='masseditproduct'}</span>
                </label>
                <label for="is_active_on">
                    <input type="radio" name="is_active" value="1" id="is_active_on"/>
                    <span>{l s='Yes' mod='masseditproduct'}</span>
                </label>
                <label for="is_active_off">
                    <input type="radio" name="is_active" value="0" id="is_active_off"/>
                    <span>{l s='No' mod='masseditproduct'}</span>
                </label>
            </div>
        </div>
    </div>
    <div class="row enabled_option_stage">

        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked="" type="checkbox" name="disabled[]" value="on_sale" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-sm-12 clearfix form-group">
            <div class="md-checkbox">
                <label class="control-label">
                    <input type="checkbox" name="on_sale" class="tab4-checkbox" value="1"/>
                    <i class="md-checkbox-control"></i>
                    {l s='Display the "on sale" icon on the product page, and in the text found within the product listing' mod='masseditproduct'}
                </label>
            </div>
        </div>
    </div>
    <div class="row enabled_option_stage">
        <div class="col-sm-12 clearfix">
            <label class="control-label margin-right">{l s='Visibility' mod='masseditproduct'}:</label>
            <select class="fixed-width-xl custom-select form-group" name="visibility">
                <option selected value="-1">{l s='Do nothing' mod='masseditproduct'}</option>
                <option value="both">{l s='Both' mod='masseditproduct'}</option>
                <option value="catalog">{l s='Only catalog' mod='masseditproduct'}</option>
                <option value="search">{l s='Only search' mod='masseditproduct'}</option>
                <option value="none">{l s='Nothing' mod='masseditproduct'}</option>
            </select>
        </div>
    </div>
    <div class="row enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="available_for_order,show_price,online_only" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-sm-12 clearfix">
            <label class="control-label options-label margin-right float-left">{l s='Options' mod='masseditproduct'}:</label>

            <div class="float-left form-group margin-right">
                <span class="md-checkbox">
                    <label class="control-label">
                        <input checked type="checkbox" name="available_for_order">
                        <i class="md-checkbox-control"></i>
                        {l s='Available for order' mod='masseditproduct'}
                    </label>
                </span>
            </div>

            <div class="float-left form-group margin-right">
                <span class="md-checkbox">
                    <label class="control-label">
                        <input checked disabled type="checkbox" name="show_price">
                        <i class="md-checkbox-control"></i>
                        {l s='Show price' mod='masseditproduct'}
                    </label>
                </span>
            </div>

            <div class="float-left form-group">
                <span class="md-checkbox">
                    <label class="control-label">
                        <input type="checkbox" name="online_only">
                        <i class="md-checkbox-control"></i>
                        {l s='Online only' mod='masseditproduct'}
                    </label>
                </span>
            </div>

        </div>
    </div>
    <div class="row enabled_option_stage">
        <div class="col-sm-12 clearfix">
            <label class="control-label margin-right">{l s='Condition' mod='masseditproduct'}:</label>
            <select class="fixed-width-xl custom-select form-group" name="condition">
                <option selected value="-1">{l s='Do nothing' mod='masseditproduct'}</option>
                <option value="new">{l s='New' mod='masseditproduct'}</option>
                <option value="used">{l s='Used' mod='masseditproduct'}</option>
                <option value="refurbished">{l s='Refurbished' mod='masseditproduct'}</option>
            </select>
        </div>
    </div>
    <div class="row enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="delete_product" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-lg-12">

            <div class="form-group float-left margin-right">
                <span class="md-checkbox">
                    <label>
                        <input checked="" type="checkbox" name="available_for_order">
                        <i class="md-checkbox-control"></i>
                        {l s='Delete selected' mod='masseditproduct'}:
                    </label>
                </span>
            </div>

            <div class="form-group float-left margin-right">
                <p class="radio form-check form-check-radio margin-right">
                    <label class="form-check-label">
                        <input type="radio" name="delete_type" checked value="0">
                        <i class="form-check-round"></i>
                        {l s='products' mod='masseditproduct'}
                    </label>
                </p>

                <p class="radio form-check form-check-radio margin-right">
                    <label class="form-check-label">
                        <input type="radio" name="delete_type" value="1">
                        <i class="form-check-round"></i>
                        {l s='combinations' mod='masseditproduct'}
                    </label>
                </p>
            </div>

        </div>
    </div>
  {if $smarty.const._PS_VERSION_ >= 1.7}
      <div class="row enabled_option_stage form-group">
          <span class="md-checkbox disable_option_wrap">
              <label>
                  <input checked="" type="checkbox" name="disabled[]" value="show_condition" class="disable_option">
                  <i class="md-checkbox-control"></i>
              </label>
          </span>
          <div class="col-lg-12 form-group">
              <div class="md-checkbox">
                  <label class="control-label">
                      <input type="checkbox" name="show_condition" class="tab4-checkbox" value="1"/>
                      <i class="md-checkbox-control"></i>
                      {l s='Display condition on product page (New, Used, Refurbished)' mod='masseditproduct'}
                  </label>
              </div>
          </div>
      </div>
  {/if}
{/block}