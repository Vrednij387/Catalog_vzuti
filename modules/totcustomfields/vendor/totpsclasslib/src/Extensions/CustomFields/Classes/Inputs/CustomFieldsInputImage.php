<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL 202 ecommerce
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL 202 ecommerce is strictly forbidden.
 * In order to obtain a license, please contact us: tech@202-ecommerce.com
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe 202 ecommerce
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de la SARL 202 ecommerce est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter 202-ecommerce <tech@202-ecommerce.com>
 * ...........................................................................
 *
 * @author    202-ecommerce <tech@202-ecommerce.com>
 * @copyright Copyright (c) 202-ecommerce
 * @license   Commercial license
 */

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs;

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputImageService;
use Configuration;
use Context;
use ImageManager;
use ImageType;
use Language;
use Translate;
use Validate;

class CustomFieldsInputImage extends CustomFieldsInput
{
    /*
     * Configuration array(
     *      'size_type' => int,
     *      'standard_size' => int, // And PS id_image_type
     *      'size_width' => int,
     *      'size_height' => int,
     *      'translatable' => bool // Received during configuration, but not in array
     * )
     */

    protected static $inputHtmlTemplateName = 'imageInput.tpl';

    protected $moduleUploadPath = 'uploads/image/';

    protected $inputImageService;

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);
        $this->inputImageService = new CustomFieldsInputImageService();
    }

    public function getListPosition()
    {
        return 3;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeCode()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeName()
    {
        return $this->l('Image', 'CustomFieldsInputImage');
    }

    public function installSQL()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function setConfigurationByArray($newConf)
    {
        $currentConf = $this->getConfigurationArray();
        $subformData = $this->getStaticConfigurationSubformData();
        if (!isset($subformData['size_types'][$newConf['size_type']])) {
            return [$this->l('The specified size type is invalid.', 'CustomFieldsInputImage')];
        }

        if ($newConf['size_type'] == 2) {
            $newConf['size_height'] = (int) $newConf['size_height'];
            if ($newConf['size_height'] <= 0) {
                return [$this->l('The specified height is invalid.', 'CustomFieldsInputImage')];
            }
            $newConf['size_height'] = min($newConf['size_height'], 9999);

            $newConf['size_width'] = (int) $newConf['size_width'];
            if ($newConf['size_width'] <= 0) {
                return [$this->l('The specified width is invalid.', 'CustomFieldsInputImage')];
            }

            $newConf['size_width'] = min($newConf['size_width'], 9999);
        } elseif ($newConf['size_type'] == 1) {
            $imageType = new ImageType($newConf['standard_size']);
            if (!Validate::isLoadedObject($imageType)) {
                return [$this->l('The specified size is invalid.', 'CustomFieldsInputImage')];
            }
        }

        if (!empty($_FILES['type_configuration']['name'][$this->getInputTypeCode()]['default_value'])) {
            $fileInfo = [
                'name' => $_FILES['type_configuration']['name'][$this->getInputTypeCode()]['default_value'],
                'type' => $_FILES['type_configuration']['type'][$this->getInputTypeCode()]['default_value'],
                'tmp_name' => $_FILES['type_configuration']['tmp_name'][$this->getInputTypeCode()]['default_value'],
                'error' => $_FILES['type_configuration']['error'][$this->getInputTypeCode()]['default_value'],
                'size' => $_FILES['type_configuration']['size'][$this->getInputTypeCode()]['default_value'],
            ];

            if (($error = ImageManager::validateUpload($fileInfo)) !== false) {
                return [
                    $this->l('Field', 'CustomFieldsInputImage') . ' ' . $this->name . ' : ' . $error,
                ];
            }

            // We'll never resize an image here; we'll use CSS to control the display
            $uploadDir = _PS_MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath;

            // Remove old file
            @unlink($uploadDir . $currentConf['default_value']);

            // Add new file
            $fileExtension = pathinfo($_FILES['type_configuration']['name'][$this->getInputTypeCode()]['default_value'], PATHINFO_EXTENSION);
            $finalFilename = md5($this->id . '_default' . time()) . '.' . $fileExtension;
            if (!move_uploaded_file($_FILES['type_configuration']['tmp_name'][$this->getInputTypeCode()]['default_value'], $uploadDir . $finalFilename)) {
                return [
                    sprintf($this->l('Field %s : Server error when uploading file %s.', 'CustomFieldsInputImage'), $this->name, $_FILES['type_configuration']['name'][$this->getInputTypeCode()]['default_value']),
                ];
            }

            // Update configuration
            $this->default_value = $finalFilename;
        } elseif (!empty($newConf['default_value']['delete'])) {
            // Remove default file
            $uploadDir = _PS_MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath;
            @unlink($uploadDir . $currentConf['default_value']);
            $this->default_value = null;
            unset($newConf['default_value']);
        }

        // If the input BECOMES translatable
        if (!$this->is_translatable && !empty($newConf['translatable'])) {
            // Copy untranslated values to default language values
            // However, this also means copying the images
            $uploadDir = _PS_MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath;
            $idLangDefault = Configuration::get('PS_LANG_DEFAULT');
            $inputValues = $this->inputImageService->getAllValuesWithLang($this->id, null);
            foreach ($inputValues as $inputValue) {
                $defaultLangValue = $this->inputImageService->getInputValue($this->id, $inputValue->id_object, $idLangDefault);
                if ($defaultLangValue->id) {
                    continue;
                }

                $fileExtension = pathinfo($uploadDir . $inputValue->value, PATHINFO_EXTENSION);
                $finalFilename = md5($this->id . '_' . $inputValue->id_object . '_' . $idLangDefault . time()) . '.' . $fileExtension;
                if (!copy($uploadDir . $inputValue->value, $uploadDir . $finalFilename)) {
                    return [
                        sprintf($this->l('Field %s : Server error when copying default language image.', 'CustomFieldsInputImage'), $this->name),
                    ];
                }

                $defaultLangValue->value = $finalFilename;
                $defaultLangValue->save();
            }
        }

        $this->is_translatable = !empty($newConf['translatable']);
        unset($newConf['translatable']); // Don't save this in configuration array, there's a field for that

        $this->configurationArray = $newConf;

        return $this->save() == true;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $value an array containing the name of the file and a "delete" flag
     *
     * @throws \PrestaShopException
     */
    public function setValue($value, $idObject)
    {
        $isValueValid = $this->isValidValue($value, $idObject);
        $idLangDefault = Configuration::get('PS_LANG_DEFAULT');
        if (true !== $isValueValid) {
            return $isValueValid;
        }

        // We'll never resize an image here; we'll use CSS to control the display
        $uploadDir = _PS_MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath;

        if (!$this->is_translatable) {
            $filename = $value['filename'];
            $isToDelete = !empty($value['delete']);

            // Remove old file
            $inputValue = $this->inputImageService->getInputValue($this->id, $idObject, null);
            if ($inputValue->id) {
                if ($filename != $inputValue->value || $isToDelete) {
                    $inputValue->value = '';
                    $inputValue->save();
                } else {
                    return true; // If value is unchanged, do nothing
                }
            }

            // Add new file
            $finalFilename = '';
            if (!empty($_FILES['totcustomfields_inputs']['name'][$this->id])) {
                $file_extension = pathinfo($_FILES['totcustomfields_inputs']['name'][$this->id], PATHINFO_EXTENSION);
                $finalFilename = md5($this->id . '_' . $idObject . time()) . '.' . $file_extension;
                if (!move_uploaded_file($_FILES['totcustomfields_inputs']['tmp_name'][$this->id], $uploadDir . $finalFilename)) {
                    return [
                        sprintf($this->l('Field %s : Server error when uploading file %s.', 'CustomFieldsInputImage'), $this->name, $_FILES['totcustomfields_inputs']['name'][$this->id]),
                    ];
                }
            } elseif ($this->default_value[$idLangDefault] && !$isToDelete) {
                $file_extension = pathinfo($uploadDir . $this->default_value[$idLangDefault], PATHINFO_EXTENSION);
                $finalFilename = md5($this->id . '_' . $idObject . time()) . '.' . $file_extension;
                if (!copy($uploadDir . $this->default_value[$idLangDefault], $uploadDir . $finalFilename)) {
                    return [
                        sprintf($this->l('Field %s : Server error when copying default file.', 'CustomFieldsInputImage'), $this->name),
                    ];
                }
            }
            if (empty($finalFilename) && $this->required) {
                return [
                    sprintf($this->l('Field %s : The field is required, you cannot left empty field', 'CustomFieldsInputImage'), $this->name),
                ];
            }

            // Update DB
            $inputValue = $this->inputImageService->getInputValue($this->id, $idObject, null);
            $inputValue->value = $finalFilename;
            $inputValue->save();
        } else {
            $errors = [];
            foreach ($value as $idLang => $val) {
                $filename = $val['filename'];
                $isToDelete = !empty($val['delete']);

                // Every language might not have a value; skip those who don't
                // isValidValue() takes care of checking which language is required
                if (!$filename && !$this->default_value[$idLangDefault] && !$isToDelete) {
                    continue;
                }

                // Remove old file
                $inputValue = $this->inputImageService->getInputValue($this->id, $idObject, $idLang);
                if ($inputValue->id) {
                    if ($filename != $inputValue->value || $isToDelete) {
                        $inputValue->value = '';
                        $inputValue->save();
                    } else {
                        continue; // If value is unchanged, do nothing
                    }
                }

                // Add new file
                $finalFilename = '';
                if (!empty($_FILES['totcustomfields_inputs']['name'][$this->id][$idLang])) {
                    $file_extension = pathinfo($_FILES['totcustomfields_inputs']['name'][$this->id][$idLang], PATHINFO_EXTENSION);
                    $finalFilename = md5($this->id . '_' . $idObject . '_' . $idLang . time()) . '.' . $file_extension;
                    if (!move_uploaded_file($_FILES['totcustomfields_inputs']['tmp_name'][$this->id][$idLang], $uploadDir . $finalFilename)) {
                        $errors[] = sprintf($this->l('Field %s : Server error when uploading file %s.', 'CustomFieldsInputImage'), $this->name, $_FILES['totcustomfields_inputs']['name'][$this->id][$idLang]);
                        continue;
                    }
                } elseif ($this->default_value[$idLangDefault] && !$isToDelete) {
                    $file_extension = pathinfo($uploadDir . $this->default_value[$idLangDefault], PATHINFO_EXTENSION);
                    $finalFilename = md5($this->id . '_' . $idObject . '_' . $idLang . time()) . '.' . $file_extension;
                    if (!copy($uploadDir . $this->default_value[$idLangDefault], $uploadDir . $finalFilename)) {
                        $errors[] = sprintf($this->l('Field %s : Server error when copying default file for language %s.', 'CustomFieldsInputImage'), $this->name, Language::getIsoById($idLang));
                        continue;
                    }
                }
                if (empty($finalFilename) && $this->required) {
                    $errors[] = sprintf($this->l('Field %s : The field is required, you cannot left empty field for language %s.', 'CustomFieldsInputImage'), $this->name, Language::getIsoById($idLang));
                    continue;
                }

                // Update DB
                $inputValue = $this->inputImageService->getInputValue($this->id, $idObject, $idLang);
                $inputValue->value = $finalFilename;
                $inputValue->save();
            }

            return !empty($errors) ? $errors : true;
        }
    }

    /**
     * Gets the input value from DB
     *
     * @param mixed $idObject
     * @param mixed $idLang when input is translatable :
     *                      if false, will return all saved translatable values;
     *                      else, will return value for corresponding id_lang
     *
     * @return mixed
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getValue($idObject, $idLang = false)
    {
        if (!$this->is_translatable) {
            $inputValue = $this->inputImageService->getInputValue($this->id, $idObject, null);

            return $inputValue->value;
        }

        if (false !== $idLang) {
            $inputValue = $this->inputImageService->getInputValue($this->id, $idObject, $idLang);
            // If we don't have a value for required lang, get default one
            if (!Validate::isLoadedObject($inputValue)) {
                $inputValue = $this->inputImageService->getInputValue($this->id, $idObject, Configuration::get('PS_LANG_DEFAULT'));
            }

            return $inputValue->value;
        }

        $inputValues = $this->inputImageService->getInputTranslatableValues($this->id, $idObject);
        if (!$inputValues) {
            return [];
        }

        $inputValuesLang = [];
        foreach ($inputValues as $inputValue) {
            /* @var $inputValue CustomFieldsInputImageValue */
            $inputValuesLang[$inputValue->id_lang] = $inputValue->value;
        }

        return $inputValuesLang;
    }

    /**
     * @param mixed $idObject
     * @param string|null|null $customTemplate
     *
     * @return mixed
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @throws \SmartyException
     */
    public function getValueHtml($idObject, $customTemplate = null)
    {
        $value = $this->getValue($idObject, $this->is_translatable ? Context::getContext()->language->id : null);
        $idLangDefault = (int) Configuration::get('PS_LANG_DEFAULT');

        // If we don't have a value for a translatable field,
        // attempt to get it with the default language
        if (!$value && ((!$this->required && $value !== '') || $this->required) && $this->is_translatable) {
            if ($idLangDefault != (int) Context::getContext()->language->id) {
                $value = $this->getValue($idObject, $idLangDefault);
            }
        }

        if (is_null($value) && ((!$this->required && $value !== '') || $this->required)) {
            $value = $this->default_value[$this->is_translatable ? Context::getContext()->language->id : $idLangDefault];
        }

        if (!$value) {
            return '';
        }

        $imgLink = _MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath . $value;

        // Use CSS to control the image display size
        $size = $this->getMaxWidthAndHeight();

        if ($customTemplate != null && file_exists($customTemplate)) {
            $tpl = Context::getContext()->smarty->createTemplate($customTemplate);
        } else {
            $tpl = Context::getContext()->smarty->createTemplate($this->getValueHtmlTemplatePath());
        }

        $tpl->caching = 0;
        $tpl->assign([
            'value' => $imgLink,
            'width' => $size['width'],
            'height' => $size['height'],
        ]);

        return $tpl->fetch();
    }

    /**
     * {@inheritdoc}
     *
     * @param int $idObject
     * @param string $codeObject
     *
     * @return string
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @throws \SmartyException
     */
    public function getInputHtml($idObject, $codeObject)
    {
        $idLangDefault = Configuration::get('PS_LANG_DEFAULT');
        $currentLang = Context::getContext()->language->id;

        // Retrieve values
        $inputValues = $this->getValue((int) $idObject);
        $hasRealValue = null; // As opposed to default values; used to hide the "delete" button
        $languageIds = Language::getLanguages(true, false, true);

        if ($inputValues) {
            // We have values
            $value = $inputValues;
            if ($this->is_translatable) {
                $imgLink = [];
                $hasRealValue = [];
                foreach ($value as $idLang => $valueLang) {
                    // We might have empty values (we shouldn't, but we might)
                    if ($valueLang) {
                        $imgLink[$idLang] = _MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath . $valueLang;
                        $hasRealValue[$idLang] = true;
                    } else {
                        $hasRealValue[$idLang] = false;
                    }
                }

                /** @var int $idLang */
                foreach ($languageIds as $idLang) {
                    // If we have a default value
                    if (!isset($value[$idLang])) {
                        if ($this->default_value) {
                            // Make sure we use it for unset languages
                            $value[$idLang] = $this->default_value[$idLang];
                            $imgLink[$idLang] = _MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath . $this->default_value;
                            $hasRealValue[$idLang] = true;
                        } else {
                            $hasRealValue[$idLang] = false;
                        }
                    }
                }
            } else {
                $imgLink = _MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath . $value;
                $hasRealValue = true;
            }
        } elseif ($this->default_value) {
            // We don't have any saved values, but we have a default one; use it everywhere
            if ($this->is_translatable) {
                $value = array_fill_keys($languageIds, $this->default_value[$idLangDefault]);
                $imgLink = array_fill_keys($languageIds, _MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath . $this->default_value[$idLangDefault]);
                $hasRealValue = array_fill_keys($languageIds, true);
            } else {
                $value = $this->default_value;
                $imgLink = _MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath . $this->default_value[$idLangDefault];
                $hasRealValue = true;
            }
        } else {
            // We don't have any values
            if ($this->is_translatable) {
                $value = [];
                $imgLink = [];
                $hasRealValue = array_fill_keys($languageIds, false);
            } else {
                $value = '';
                $imgLink = '';
                $hasRealValue = false;
            }
        }

        $instructions = '';
        if (!empty($this->instructions[$currentLang])) {
            $instructions = $this->instructions[$currentLang];
        } elseif (!empty($this->instructions[$idLangDefault])) {
            $instructions = $this->instructions[$idLangDefault];
        }

        $tpl = Context::getContext()->smarty->createTemplate($this->getInputHtmlTemplatePath($codeObject));
        $tpl->caching = 0;
        $tpl->assign([
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'required' => $this->required,
            'instructions' => $instructions,
            'value' => $value,
            'translatable' => $this->is_translatable,
            'defaultLang' => $idLangDefault,
            'currentLang' => $currentLang,
            'img_link' => $imgLink,
            'hasRealValue' => $hasRealValue,
            'authorized_extensions' => ['gif', 'jpg', 'jpeg', 'jpe', 'png'],
            'module_path' => _MODULE_DIR_ . 'totcustomfields/',
        ]);

        if ($this->is_translatable) {
            $tpl->assign('languages', Context::getContext()->controller->getLanguages());
        }

        return $tpl->fetch();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopDatabaseException
     */
    public function getStaticConfigurationSubformData()
    {
        $imageTypes = [];
        foreach (ImageType::getImagesTypes() as $row) {
            $imageTypes[$row['id_image_type']] = $row['name'] . ' (' . $row['width'] . 'x' . $row['height'] . ' px)';
        }

        return [
            'size_types' => [
                1 => ['name' => $this->l('Standard', 'CustomFieldsInputImage')],
                2 => ['name' => $this->l('Custom', 'CustomFieldsInputImage')],
            ],
            'standard_sizes' => $imageTypes,
        ];
    }

    /**
     * @return mixed
     *
     * @throws \PrestaShopDatabaseException
     */
    public function getConfigurationSubformData()
    {
        $data = $this->getStaticConfigurationSubformData();
        $conf = $this->getConfigurationArray();

        $data['currentConf'] = [
            'size_type' => $conf['size_type'],
            'standard_size' => $conf['standard_size'],
            'size_width' => $conf['size_width'],
            'size_height' => $conf['size_height'],
            'default_value' => is_array($this->default_value)
                ? $this->default_value[Configuration::get('PS_LANG_DEFAULT')]
                : $this->default_value,
            'translatable' => $this->is_translatable,
            'images_path' => _MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath,
        ];

        return $data;
    }

    /**
     * Validates an upload using PS ImageManager
     *
     * @param array $value not used much, it's the input/text value. We're checking the $_FILES really.
     * @param int|null $idObject
     *
     * @return true|array true or an array of errors
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function isValidValue(&$value, $idObject = null)
    {
        /** @var array $value an array containing the name of the file and a "delete" flag */

        // Because we do not have a file sent does not mean we don't have one;
        // Use current value to check if required field is valid or not
        $currentValue = $this->getValue($idObject);

        if (!$this->is_translatable) {
            $filename = $value['filename'];
            $toDelete = !empty($value['delete']);

            if ($this->required && empty($filename) && (empty($currentValue) || $toDelete)) {
                return [
                    sprintf($this->l('Field %s : No value specified for required field', 'CustomFieldsInputImage'), $this->name),
                ];
            } elseif (!empty($filename) && $filename != $this->default_value) {
                $file_info = [
                    'name' => $_FILES['totcustomfields_inputs']['name'][$this->id],
                    'type' => $_FILES['totcustomfields_inputs']['type'][$this->id],
                    'tmp_name' => $_FILES['totcustomfields_inputs']['tmp_name'][$this->id],
                    'error' => $_FILES['totcustomfields_inputs']['error'][$this->id],
                    'size' => $_FILES['totcustomfields_inputs']['size'][$this->id],
                ];

                if (!empty($file_info['name'])) {
                    if (($error = ImageManager::validateUpload($file_info)) !== false) {
                        return ['Field ' . $this->name . ' : ' . $error];
                    }
                }
            }
        } else {
            $idLangDefault = (int) Configuration::get('PS_LANG_DEFAULT');

            // If the field is required
            if ($this->required
                // And we're not uploading a file
                && empty($value[$idLangDefault]['filename'])
                && (
                    // And there's no current file
                    empty($currentValue[$idLangDefault])
                    // Or there is a current file, but we're deleting it
                    || !empty($value[$idLangDefault]['delete'])
                )
            ) {
                // The input won't have a value for the default language
                return [
                    sprintf($this->l('Field %s : Missing value for default language "%s"', 'CustomFieldsInputImage'), $this->name, Language::getIsoById($idLangDefault)),
                ];
            }

            foreach ($value as $id_lang => $val) {
                $filename = $val['filename'];
                if ($filename != $this->default_value) {
                    $file_info = [
                        'name' => $_FILES['totcustomfields_inputs']['name'][$this->id][$id_lang],
                        'type' => $_FILES['totcustomfields_inputs']['type'][$this->id][$id_lang],
                        'tmp_name' => $_FILES['totcustomfields_inputs']['tmp_name'][$this->id][$id_lang],
                        'error' => $_FILES['totcustomfields_inputs']['error'][$this->id][$id_lang],
                        'size' => $_FILES['totcustomfields_inputs']['size'][$this->id][$id_lang],
                    ];

                    if (!empty($file_info['name'])) {
                        if (($error = ImageManager::validateUpload($file_info)) !== false) {
                            return ['Field ' . $this->name . ' : ' . $error];
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Returns the max width/height for this image, according to configuration
     *
     * @return array|false like array('width' => X, 'height' => Y)
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    protected function getMaxWidthAndHeight()
    {
        $conf = $this->getConfigurationArray();
        switch ($conf['size_type']) {
            case 1:
                $imageType = new ImageType($conf['standard_size']);

                return ['width' => $imageType->width, 'height' => $imageType->height];
            case 2:
                return ['width' => $conf['size_width'], 'height' => $conf['size_height']];
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @param int $idInput
     * @param string|null $value
     *
     * @return string
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @throws \SmartyException
     */
    public function formatValueForAdminSummary($idInput, $value)
    {
        if (!$value) {
            return '';
        }

        $input = new CustomFieldsInputImage($idInput);
        if (!Validate::isLoadedObject($input)) {
            return '';
        }

        $imgLink = _MODULE_DIR_ . 'totcustomfields/' . $input->moduleUploadPath . $value;

        // Use CSS to control the image display size
        $tpl = Context::getContext()->smarty->createTemplate($this->getValueHtmlTemplatePath());
        $tpl->caching = 0;
        $tpl->assign([
            'value' => $imgLink,
            'width' => 50,
            'height' => 200,
        ]);

        return $tpl->fetch();
    }

    public function delete()
    {
        $return = true;

        // Delete values
        $values = $this->inputImageService->getAllInputValues($this->id);
        $uploadDir = _PS_MODULE_DIR_ . 'totcustomfields/' . $this->moduleUploadPath;
        foreach ($values as $val) {
            /* @var CustomFieldsInputImageValue $val */
            // Also delete files
            @unlink($uploadDir . $val->value);
            $return &= $val->delete();
        }

        return $return && parent::delete();
    }

    public function getValueHtmlTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/front/custom_fields/inputs/valueImageInput.tpl';
    }

    public function getConfigurationSubformTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/config/formImageInput.tpl';
    }
}
