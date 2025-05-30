<?php
/**
 * 2017 IQIT-COMMERCE.COM
 *
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement
 *
 *  @author    IQIT-COMMERCE.COM <support@iqit-commerce.com>
 *  @copyright 2017 IQIT-COMMERCE.COM
 *  @license   Commercial license (You can not resell or redistribute this software.)
 *
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

require_once dirname(__FILE__).'/src/IqitThreeSixty.php';
require_once dirname(__FILE__).'/src/IqitProductVideo.php';

class IqitExtendedProduct extends Module implements WidgetInterface
{
    const INSTALL_SQL_FILE = '/sql/install.sql';
    const UNINSTALL_SQL_FILE = '/sql/uninstall.sql';
    const ACCESS_RIGHTS = 0775;

    protected $SOURCE_INDEX;
    protected $UPLOAD_DIR;
    protected $templateFile;

    public function __construct()
    {
        $this->name = 'iqitextendedproduct';
        $this->author = 'IQIT-COMMERCE.COM';
        $this->tab = 'front_office_features';
        $this->version = '1.1.2';
        $this->cfgName = 'iqitextendedp_';
        $this->defaults = array(
            'bg' => '#151515',
            'color' => '#ffffff',
        );

        $this->SOURCE_INDEX = _PS_PROD_IMG_DIR_ . 'index.php';
        $this->UPLOAD_DIR = _PS_MODULE_DIR_ . 'iqitextendedproduct/uploads/';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('IQITEXTENDEDPRODUCT - 360 degree image rotation and videos');
        $this->description = $this->l('Extend your product presentation with additional features');
        $this->templateFile = 'module:'.$this->name.'/views/templates/hook/iqitextendedproduct_front.tpl';
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayAfterProductThumbs')
            || !$this->registerHook('displayAsLastProductImage')
            || !$this->registerHook('displayAsFirstProductImage')
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('actionObjectProductUpdateAfter')
            || !$this->registerHook('actionObjectProductDeleteAfter')
            || !$this->registerHook('actionObjectProductAddAfter')
            || !$this->installSQL()) {
            return false;
        }

        Configuration::updateValue('iqitextendedproduct_speed', '70');
        Configuration::updateValue('iqitextendedproduct_hook', 'modal');

        foreach ($this->defaults as $default => $value) {
            if ($default == 'content') {
                $message_trads = array();
                foreach (Language::getLanguages(false) as $lang) {
                    $message_trads[(int) $lang['id_lang']] = $value;
                }
                Configuration::updateValue($this->cfgName . $default, $message_trads, true);
            } else {
                Configuration::updateValue($this->cfgName . $default, $value);
            }
        }
        return true;
    }

    public function isUsingNewTranslationSystem()
    {
        return false;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->registerStylesheet('modules-'.$this->name.'-style', 'modules/'.$this->name.'/views/css/front.css', ['media' => 'all', 'priority' => 150]);
        $this->context->controller->registerJavascript('modules'.$this->name.'-script', 'modules/'.$this->name.'/views/js/front.js', ['position' => 'bottom', 'priority' => 150]);

        Media::addJsDef(array('iqitextendedproduct' => [
            'speed' => Configuration::get('iqitextendedproduct_speed'),
            'hook' => Configuration::get('iqitextendedproduct_hook'),
        ]));
    }

    public function uninstall()
    {
        foreach ($this->defaults as $default => $value) {
            Configuration::deleteByName($this->cfgName . $default);
        }

        return $this->uninstallSQL() && parent::uninstall();
    }


    public function hookDisplayAdminProductsExtra($params)
    {
        $idProduct = (int) Tools::getValue('id_product', $params['id_product']);

        $idThreeSixty = IqitThreeSixty::getIdByProduct($idProduct);
        $threeSixty = new IqitThreeSixty($idThreeSixty);

        $threeSixtyContent = array();
        if (Validate::isLoadedObject($threeSixty)) {
            foreach ($threeSixty->content as $key => $image) {
                $threeSixtyContent[$key]['path'] = $this->_path.'uploads/threesixty/'.$this->getFolder($idProduct).'/'.$image['n'];
                $threeSixtyContent[$key]['name'] = $image['n'];
            }

        }

        $idProductVideo = IqitProductVideo::getIdByProduct($idProduct);
        $productVideo = new IqitProductVideo($idProductVideo);
        $productVideoContent = array();

        if (Validate::isLoadedObject($productVideo)) {
            $productVideoContent = $productVideo->content;
        }

        $this->context->smarty->assign(array(
            'product' =>$idProduct,
            'path' => $this->_path,
            'bAdminUrl' => __PS_BASE_URI__.basename(_PS_ADMIN_DIR_).'/',
            'threeSixtyContent' => $threeSixtyContent,
            'productVideoContent' => $productVideoContent,
            'threeSixtyActionUrl' => $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name . '&action=UploaderThreeSixty&ajax=1&id_product=' . $idProduct,
        ));

        return $this->display(__FILE__, 'views/templates/admin/bo_productab.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        if ($this->context->controller->controller_name == 'AdminProducts') {
            $this->context->controller->addCSS($this->_path . 'views/css/admin_tab.css');

            $base_url = Tools::getHttpHost(true);  // DON'T TOUCH (base url (only domain) of site (without final /)).
            $base_url = Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE') ? $base_url : str_replace('https', 'http', $base_url);
    
            Media::addJsDef(array(
                'iqitModulesExtenedProduct' => [
                    'iqitBaseUrl' => Tools::safeOutput($base_url)
                ]
            ));

        }
    }

    public function ajaxProcessUploaderThreeSixty()
    {
        $idProduct = (int) Tools::getValue('id_product');
        $folder = 'threesixty/';

        $product = new Product((int) $idProduct);
        if (!Validate::isLoadedObject($product)) {
            $files = array();
            $files[0]['error'] = Tools::displayError('Cannot add image because product creation failed.');
        }
        header('Content-Type: application/json');
        $step = (int) Tools::getValue('step');

        if ($step == 1) {
            $image_uploader = new HelperImageUploader('threesixty-file-upload');
            $image_uploader->setAcceptTypes(array('jpeg', 'gif', 'png', 'jpg'));
            $files = $image_uploader->process();
            $new_destination = $this->getPathForCreation($idProduct, $folder);

            foreach ($files as &$file) {
                $filename = uniqid() . '.jpg';
                $error = 0;
                if (!ImageManager::resize($file['save_path'], $new_destination . $filename, null, null, 'jpg', false, $error)) {
                    switch ($error) {
                        case ImageManager::ERROR_FILE_NOT_EXIST:
                        $file['error'] = Tools::displayError('An error occurred while copying image, the file does not exist anymore.');
                        break;
                        case ImageManager::ERROR_FILE_WIDTH:
                        $file['error'] = Tools::displayError('An error occurred while copying image, the file width is 0px.');
                        break;
                        case ImageManager::ERROR_MEMORY_LIMIT:
                        $file['error'] = Tools::displayError('An error occurred while copying image, check your memory limit.');
                        break;
                        default:
                        $file['error'] = Tools::displayError('An error occurred while copying image.');
                        break;
                    }
                    continue;
                }
                unlink($file['save_path']);
                unset($file['save_path']);
                $file['status'] = 'ok';
                $file['name'] = $filename;
            }
            die(json_encode($files[0]));
        } elseif ($step == 2) {
            $file = (string) Tools::getValue('file');
            if (file_exists($this->UPLOAD_DIR . $folder . $idProduct . '/' . $file)) {
                $res = @unlink($this->UPLOAD_DIR . $folder . $idProduct . '/' . $file);
            }
            if ($res) {
                die('ok');
            } else {
                die('error');
            }
        }
    }

    private function getPathForCreation($id_product, $folder)
    {
        $path = $this->getFolder($id_product);
        $this->createFolder($id_product, $this->UPLOAD_DIR . $folder);
        return $this->UPLOAD_DIR . $folder . $path;
    }

    private function createFolder($id_product, $folder)
    {
        if (!file_exists($folder . $this->getFolder($id_product))) {
            $success = @mkdir($folder . $this->getFolder($id_product), self::ACCESS_RIGHTS, true);
            $chmod = @chmod($folder . $this->getFolder($id_product), self::ACCESS_RIGHTS);
            if (($success || $chmod)
                && !file_exists($folder . $this->getFolder($id_product) . 'index.php')
                && file_exists($this->SOURCE_INDEX)) {
                return @copy($this->SOURCE_INDEX, $folder . $this->getFolder($id_product) . 'index.php');
            }
        }
        return true;
    }

    private function getFolder($id_product)
    {
        if (!is_numeric($id_product)) {
            return false;
        }
        $folders = str_split((string) $id_product);
        return implode('/', $folders) . '/';
    }

    private function deleteFolder($id_product, $folder)
    {
        $path = $this->getPathForCreation($id_product, $folder);
        if (is_dir($path)) {
            $deleteFolder = true;
        }
        if (isset($deleteFolder) && $deleteFolder) {
            array_map('unlink', glob($path.'*.*'));
            @rmdir($path);
        }
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }

        $idProduct = (int) $configuration['smarty']->tpl_vars['product']->value['id_product'];

        $position = Configuration::get('iqitextendedproduct_hook');

        if($hookName == 'displayAsFirstProductImage'){
            if($position != 'first-image'){
                return;
            }
        } elseif($hookName == 'displayAsLastProductImage'){
            if($position != 'last-image'){
                return;
            }
        } else{
            if($position != 'modal'){
                return;
            }
        }

        $imageCarusel = 'large';

        if(isset($configuration['imageCarusel'])){
            $imageCarusel = $configuration['imageCarusel'];
        }


        $cacheId = 'iqitextendedproduct|'.$idProduct.'|'.$hookName.'|'.$imageCarusel;

        if (!$this->isCached($this->templateFile, $this->getCacheId($cacheId))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }
        return $this->fetch($this->templateFile, $this->getCacheId($cacheId));
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $imageCarusel = '';

        if(isset($configuration['imageCarusel'])){
            $imageCarusel = $configuration['imageCarusel'];
        }

        $idProduct = (int) $configuration['smarty']->tpl_vars['product']->value['id_product'];

        $idThreeSixty = IqitThreeSixty::getIdByProduct($idProduct);
        $threeSixty = new IqitThreeSixty($idThreeSixty);
        $threeSixtyContent = array();
        $isThreeSixtyContent = false;
        if (Validate::isLoadedObject($threeSixty)) {
            foreach ($threeSixty->content as $key => $image) {
                $threeSixtyContent[$key] = $this->_path.'uploads/threesixty/'.$this->getFolder($idProduct).'/'.$image['n'];
            }
            $isThreeSixtyContent = true;
        }


        $idProductVideo = IqitProductVideo::getIdByProduct($idProduct);
        $productVideo = new IqitProductVideo($idProductVideo);
        $productVideoContent = array();

        if (Validate::isLoadedObject($productVideo)) {
            $productVideoContent = $productVideo->content;
        }

        return array(
            'threeSixtyContent' => htmlspecialchars(json_encode($threeSixtyContent), ENT_COMPAT, 'UTF-8'),
            'isThreeSixtyContent' => $isThreeSixtyContent,
            'productVideoContent' => $productVideoContent,
            'path' => $this->_path,
            'idProduct' => $idProduct,
            'hookName' => $hookName,
            'imageCarusel' => $imageCarusel
        );
    }

    public function hookActionObjectProductUpdateAfter($params)
    {
        if (!isset($params['object']->id)) {
            return;
        }
        $this->joinWithProduct($params['object']->id);

        $this->clearCache($params['object']->id);
    }

    public function joinWithProduct($idProduct)
    {

        if (!isset(Tools::getValue('iqitextendedproduct')['threesixty'])){
            return;
        }

        if (!isset(Tools::getValue('iqitextendedproduct')['videos'])){
            return;
        }




        $idProduct = (int) $idProduct;

        $images = Tools::getValue('iqitextendedproduct')['threesixty'];
        $imagesArray = json_decode($images);
        $idThreeSixty = IqitThreeSixty::getIdByProduct($idProduct);
        $threeSixty = new IqitThreeSixty($idThreeSixty);

        $videos = Tools::getValue('iqitextendedproduct')['videos'];
        $idProductVideo = IqitProductVideo::getIdByProduct($idProduct);
        $productVideo = new IqitProductVideo($idProductVideo);
        $videosArray = json_decode($videos);



        if (!is_array($imagesArray) || empty($imagesArray)) {
            if (Validate::isLoadedObject($threeSixty)) {
                $threeSixty->delete();
            }
        } else {
            if (Validate::isLoadedObject($threeSixty)) {
                $threeSixty->content = $images;
                $threeSixty->update();
            } else {
                $threeSixty = new IqitThreeSixty();
                $threeSixty->id_product = $idProduct;
                $threeSixty->content = $images;
                $threeSixty->add();
            }
        }

        if (!is_array($videosArray) || empty($videosArray)) {
            if (Validate::isLoadedObject($productVideo)) {
                $productVideo->delete();
            }
        } else {
            if (Validate::isLoadedObject($productVideo)) {
                $productVideo->content = $videos;
                $productVideo->update();
            } else {
                $productVideo = new IqitProductVideo();
                $productVideo->id_product = $idProduct;
                $productVideo->content = $videos;
                $productVideo->add();
            }
        }
    }

    public function hookActionObjectProductDeleteAfter($params)
    {
        if (!isset($params['object']->id)) {
            return;
        }
        $idProduct = (int) $params['object']->id;

        $idThreeSixty = IqitThreeSixty::getIdByProduct($idProduct);
        $threeSixty = new IqitThreeSixty($idThreeSixty);
        $idProductVideo = IqitProductVideo::getIdByProduct($idProduct);
        $productVideo = new IqitProductVideo($idProductVideo);

        if (Validate::isLoadedObject($threeSixty)) {
            $threeSixty->delete();
        }
        if (Validate::isLoadedObject($productVideo)) {
            $productVideo->delete();
        }
        $this->deleteFolder($idProduct, 'threesixty/');

        $this->clearCache($idProduct);
    }

    public function hookActionObjectProductAddAfter($params)
    {
        if (!isset($params['object']->id)) {
            return;
        }
        $this->joinWithProduct($params['object']->id);
    }

    public function clearCache($idProduct = 0)
    {
        if ($idProduct) {
            $this->_clearCache($this->templateFile, 'iqitextendedproduct|' . $idProduct);
        } else {
            $this->_clearCache($this->templateFile);
        }
    }

    protected function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return '<p class="alert alert-warning">' .
            $this->l('You cannot manage module from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit') .
                '</p>';
        } else {
            return '';
        }
    }

    public function getContent()
    {
        $output = '';

        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return $this->getWarningMultishopHtml();
        }

        if (Tools::isSubmit('submitModule')) {
            Configuration::updateValue('iqitextendedproduct_speed', Tools::getValue('iqitextendedproduct_speed'));
            Configuration::updateValue('iqitextendedproduct_hook', Tools::getValue('iqitextendedproduct_hook'));
            $output .= $this->displayConfirmation($this->l('Configuration updated'));
            $this->clearCache();
        }
        $output .= $this->renderForm();
        return $output;
    }


    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('360 image rotation speed in miliseconds'),
                        'name' => 'iqitextendedproduct_speed',
                        'desc' => $this->l('Lower value = faster animation, higher value = slower animation'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('360 position'),
                        'name' => 'iqitextendedproduct_hook',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 'modal',
                                    'name' => $this->l('Modal'),
                                ),
                                array(
                                    'id_option' => 'first-image',
                                    'name' => $this->l('First image'),
                                ),
                                array(
                                    'id_option' => 'last-image',
                                    'name' => $this->l('Last image'),
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name',
                        ),
                    ),
                ),
                'submit' => array(
                    'name' => 'submitModule',
                    'title' => $this->l('Save'),
                ),
            ),
        );

        if (Shop::isFeatureActive()) {
            $fields_form['form']['description'] = $this->l('The modifications will be applied to') . ' ' . (Shop::getContext() == Shop::CONTEXT_SHOP ? $this->l('shop') . ' ' . $this->context->shop->name : $this->l('all shops'));
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'iqitextendedproduct_speed' => Tools::getValue('iqitextendedproduct_speed', Configuration::get('iqitextendedproduct_speed')),
            'iqitextendedproduct_hook' => Tools::getValue('iqitextendedproduct_hook', Configuration::get('iqitextendedproduct_hook')),
        );
    }


    /**
     * Install SQL
     * @return boolean
     */
    private function installSQL()
    {
          if (!file_exists(dirname(__FILE__) . self::INSTALL_SQL_FILE)) {
                return false;
            } elseif (!$sql = file_get_contents(dirname(__FILE__) . self::INSTALL_SQL_FILE)) {
                return false;
            }
            $sql = str_replace(array('PREFIX', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
            $sql = preg_split("/;\s*[\r\n]+/", trim($sql));
            foreach ($sql as $query) {
                if (!Db::getInstance()->execute(trim($query))) {
                    return false;
                }
            }


        // Clean memory
        unset($sql, $q, $replace);

        return true;
    }

    /**
     * Uninstall SQL
     * @return boolean
     */
    private function uninstallSQL()
    {
        if (!file_exists(dirname(__FILE__)  . self::UNINSTALL_SQL_FILE)) {
                return false;
            } elseif (!$sql = file_get_contents(dirname(__FILE__) . self::UNINSTALL_SQL_FILE)) {
                return false;
            }
            $sql = str_replace(array('PREFIX', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
            $sql = preg_split("/;\s*[\r\n]+/", trim($sql));
            foreach ($sql as $query) {
                if (!Db::getInstance()->execute(trim($query))) {
                    return false;
                }
            }
        // Clean memory
        unset($sql, $q, $replace);

        return true;
    }
}
