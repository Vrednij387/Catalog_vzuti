/**
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
 */

'use strict';
/*
    globals default_language, checkboxOptions, checkboxDefaultOptions
 */

var checkboxInputProcessor = {
    $addOptionBtn: null,
    $cancelOption: null,
    $deleteOption: null,
    $editOption: null,
    $addEditOptionBlock: null,
    isEditing: false,
    currentObject: null,
    $checkboxOptions: null,
    $checkboxDefaultOptions: null,
    $checkboxOptionsInputs: null,
    $checkboxDefaultInputs: null,
    initVariables: function () {
        this.$addOptionBtn = $('.add-edit-option-checkbox');
        this.$cancelOption = $('.cancel-option-checkbox');
        this.$deleteOption = $('.delete-selected-option-checkbox');
        this.$editOption = $('.edit-selected-option-checkbox');
        this.$optionsDefaultButton = $('.default-selected-option-checkbox');
        this.$removeDefaultButton = $('.remove-default-selected-option-checkbox');
        this.$addEditOptionBlock = $('.add-edit-checkbox-option-block');
        this.$checkboxOptions = $('.checkbox-options');
        this.$checkboxDefaultOptions = $('.checkbox-default-options');
        this.$checkboxOptionsInputs = $('.checkbox-options-inputs');
        this.$checkboxDefaultInputs = $('.checkbox-default-options-inputs');
    },
    init: function () {
        this.initVariables();
        this.registerEvents();
        this.initData();
    },
    initData() {
        if (typeof checkboxOptions !== 'undefined') {
            for (var option in checkboxOptions) {
                $('option[data-id="' + option + '"]').val(checkboxOptions[option].value);
                this.$checkboxOptionsInputs.find('input[name="type_configuration[checkbox][checkbox-option][' + option + ']"]').val(checkboxOptions[option].value);
            }
        }
        if (typeof checkboxDefaultOptions !== 'undefined') {
            for (var optionDefault in checkboxDefaultOptions) {
                $('option[data-id="' + optionDefault + '"]').val(checkboxDefaultOptions[optionDefault].value);
                this.$checkboxDefaultInputs.find('input[name="type_configuration[checkbox][checkbox-default-option][' + optionDefault + ']"]').val(checkboxDefaultOptions[optionDefault].value);
            }
        }
    },
    changeDeleteState(idDisabled) {
        this.$deleteOption.prop('disabled', idDisabled);
    },
    addOption() {
        var option = {
            name: '',
            data: []
        };
        var isEmpty = true;
        this.$addEditOptionBlock.find('input').each(function (index, item) {
            if ($(item).val() !== '') {
                isEmpty = false;
            }
            var $wrapper = $(item).closest('.checkbox-lang-wrapper');
            var langId = $wrapper.attr('id-lang');
            if (default_language === langId) {
                option.name = $(item).val();
            }

            option.data.push({
                langId: langId,
                value: $(item).val()
            });
        });
        if (!isEmpty || option.name === '') {
            option = this.postProcessOptionCreation(option);
        }

        return isEmpty ? null : option;
    },
    postProcessOptionCreation(option) {
        var defaultValue = '';
        for(var i = 0; i < option.data.length; i++) {
            if (defaultValue === '') {
                defaultValue = option.data[i]['value'];
            }
            if (option.data[i]['langId'] === default_language && option.data[i]['value'] !== '') {
                defaultValue = option.data[i]['value'];
                break;
            }
        }
        for(var j= 0; j < option.data.length; j++) {
            if (option.data[j]['value'] === '') {
                option.data[j]['value'] = defaultValue;
            }
        }
        if (option.name === '') {
            option.name = defaultValue;
        }

        return option;
    },
    clearOptionInput() {
        this.$addEditOptionBlock.find('input').each(function (index, item) {
            $(item).val('');
        });
    },
    isInDefault(optionId) {
        var idFound = false;
        this.$checkboxDefaultOptions.children().each(function (index, item) {
            if (optionId === $(item).data('id')) {
                idFound = true;
            }
        });

        return idFound;
    },
    changeAddButtonState(isEdit) {
        var $addEditButton = $('.add-edit-option-checkbox');
        if (isEdit) {
            $addEditButton.find('.button-text-add-option').addClass('hidden');
            $addEditButton.find('.button-text-edit-option').removeClass('hidden');
        } else {
            $addEditButton.find('.button-text-add-option').removeClass('hidden');
            $addEditButton.find('.button-text-edit-option').addClass('hidden');
        }
    },
    changeAddRemoveDefaultState(isInDefault) {
        var $defaultButton = $('.default-selected-option-checkbox');
        if (isInDefault) {
            $defaultButton.find('.button-text-add-default-option').addClass('hidden');
            $defaultButton.find('.button-text-remove-default-option').removeClass('hidden');
        } else {
            $defaultButton.find('.button-text-add-default-option').removeClass('hidden');
            $defaultButton.find('.button-text-remove-default-option').addClass('hidden');
        }
    },
    registerEvents: function () {
        var self = this;

        this.$addOptionBtn.on('click', function () {
            var option = self.addOption();
            if (option === null) {
                return;
            }

            var $optionElement = null,
                $hiddenInput = null,
                id = null,
                $defaultElement = null;

            if (self.isEditing) {
                $optionElement = $('option[data-id="' + self.currentObject + '"]');
                $defaultElement = self.$deleteOption.find('option[data-id="' + self.currentObject + '"]');
                $hiddenInput = $('input[name="type_configuration[checkbox][checkbox-option][' + self.currentObject + ']"]');
                id = self.currentObject;
            } else {
                $optionElement = $('<option></option>');
                $defaultElement = $('<option></option>');
                $hiddenInput = $('<input>');
                id = totcfTools.str2url(option.name, 'UTF-8');
            }

            $optionElement.text(option.name);
            $defaultElement.text(option.name);
            $optionElement.attr('value', JSON.stringify(option.data));
            $defaultElement.attr('value', id);

            $optionElement.attr('data-id', id);
            $defaultElement.attr('data-id', id);
            $hiddenInput.attr('type', 'hidden');
            $hiddenInput.attr('name', 'type_configuration[checkbox][checkbox-option][' + id + ']');
            $hiddenInput.attr('value', JSON.stringify(option.data));

            if (!self.isEditing) {
                self.$checkboxOptionsInputs.append($hiddenInput);
                self.$checkboxOptions.append($optionElement);
            } else {
                self.$checkboxDefaultInputs.find('input[name="type_configuration[checkbox][checkbox-default-option][' + id + ']"]').val(JSON.stringify(option.data));
            }

            self.isEditing = false;
            self.currentObject = null;
            self.clearOptionInput();
            self.changeDeleteState(false);
            self.changeAddButtonState(self.isEditing);
            $('.chosen').trigger('chosen:updated');
        });

        this.$cancelOption.on('click', function () {
            self.isEditing = false;
            self.currentObject = null;
            self.clearOptionInput();
            self.changeDeleteState(false);
            self.changeAddButtonState(self.isEditing);
        });

        this.$editOption.on('click', function () {
            var $selected = $('.checkbox-options option:selected');
            if ($selected.length === 0) {
                return;
            }

            var name = $selected.text();
            var data = JSON.parse($selected.val());
            $('.checkbox-lang-wrapper').each(function (index, item) {
                for (var i = 0; i < data.length; i++) {
                    if (data[i].langId === $(item).attr('id-lang')) {
                        $(item).find('input').val(data[i].value);
                    }
                }
            });

            self.isEditing = true;
            self.currentObject = $selected.attr('data-id');
            self.changeAddButtonState(self.isEditing);
            self.changeDeleteState(true);
        });

        this.$deleteOption.on('click', function () {
            var $selected = $('.checkbox-options option:selected');
            if ($selected.length === 0) {
                return;
            }
            var $hiddenInput = self.$checkboxOptionsInputs.find('input[name="type_configuration[checkbox][checkbox-option][' + $selected.attr('data-id') + ']"]');
            var $hiddenDefaultInput = self.$checkboxDefaultInputs.find('input[name="type_configuration[checkbox][checkbox-default-option][' + $selected.attr('data-id') + ']"]');
            //default TODO
            $hiddenInput.remove();
            $hiddenDefaultInput.remove();
            $selected.remove();
            self.$checkboxDefaultOptions.find('option[data-id="' + $selected.attr('data-id') + '"]').remove();

        });

        this.$removeDefaultButton.on('click', function () {
            var $selected = $('.checkbox-default-box option:selected');
            if ($selected.length === 0) {
                return;
            }

            var $hiddenDefaultInput = self.$checkboxDefaultInputs.find('input[name="type_configuration[checkbox][checkbox-default-option][' + $selected.attr('data-id') + ']"]');
            $hiddenDefaultInput.remove();
            self.$checkboxDefaultOptions.find('option[data-id="' + $selected.attr('data-id') + '"]').remove();
            self.$checkboxOptions.trigger('change');
        });

        this.$optionsDefaultButton.on('click', function () {
            var $selected = $('.checkbox-options option:selected');
            if ($selected.length === 0) {
                return;
            }

            if (self.isInDefault($selected.data('id'))) {
                var $hiddenDefaultInput = self.$checkboxDefaultInputs.find('input[name="type_configuration[checkbox][checkbox-default-option][' + $selected.attr('data-id') + ']"]');
                $hiddenDefaultInput.remove();
                self.$checkboxDefaultOptions.find('option[data-id="' + $selected.attr('data-id') + '"]').remove();
            } else {
                self.$checkboxDefaultOptions.append($selected.clone());
                $hiddenDefaultInput = $('<input>');
                $hiddenDefaultInput.attr('type', 'hidden');
                $hiddenDefaultInput.attr('name', 'type_configuration[checkbox][checkbox-default-option][' + $selected.attr('data-id') + ']');
                $hiddenDefaultInput.attr('value', $selected.val());
                self.$checkboxDefaultInputs.append($hiddenDefaultInput);
            }

            self.$checkboxOptions.trigger('change');
        });

        this.$checkboxOptions.on('change', function () {
            var $selected = $('.checkbox-options option:selected');
            if ($selected.length === 0) {
                return;
            }

            if (self.isInDefault($selected.data('id'))) {
                self.changeAddRemoveDefaultState(true);
            } else {
                self.changeAddRemoveDefaultState(false);
            }
        });
    },
};

$(function () {
    checkboxInputProcessor.init();
});