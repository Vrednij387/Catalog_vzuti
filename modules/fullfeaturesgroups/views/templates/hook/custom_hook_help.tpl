{**
* 2020 TerraNet
*
* NOTICE OF LICENSE
*
* @author    TerraNet
* @copyright 2020 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

<div class="alert alert-info">
  <p>
    <i>{l s='How to implement custom hook on the product detail page:' mod='fullfeaturesgroups'}</i>
    <br/>
      {l s='add new line:' mod='fullfeaturesgroups'}
    <b>
        {literal}
          {hook h='displayFFGFeatures' product=$product}
        {/literal}
    </b><br/>
      {l s='in the file:' mod='fullfeaturesgroups'} /themes/classic/templates/catalog/product.tpl
  </p>
  <br/>
  <p>
    <i>{l s='How to implement custom hook on the catalog page in the products miniatures:' mod='fullfeaturesgroups'}</i>
    <br/>
      {l s='add new line:' mod='fullfeaturesgroups'}
    <b>
        {literal}
          {hook h='displayFFGFeatures' product=$product}
        {/literal}
    </b>
    <br/>
      {l s='in the file:' mod='fullfeaturesgroups'} /themes/classic/templates/catalog/_partials/miniatures/product.tpl
  </p>
  <br/>
  <p>
      {l s='Custom hook template:' mod='fullfeaturesgroups'}
    /fullfeaturesgroups/views/templates/hook/custom_fullfeaturesgroups.tpl
  </p>
</div>