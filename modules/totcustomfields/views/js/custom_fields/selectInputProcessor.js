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
    globals default_language, selectOptions
 */

var selectInputProcessor = {
    $addOptionBtn: null,
    $cancelOption: null,
    $deleteOption: null,
    $editOption: null,
    $addEditOptionBlock: null,
    isEditing: false,
    currentObject: null,
    $selectOptions: null,
    $selectOptionsInputs: null,
    $selectDefault: null,
    initVariables: function () {
        this.$addOptionBtn = $('.add-edit-option-select');
        this.$cancelOption = $('.cancel-option-select');
        this.$deleteOption = $('.delete-selected-option-select');
        this.$editOption = $('.edit-selected-option-select');
        this.$addEditOptionBlock = $('.add-edit-option-block');
        this.$selectOptions = $('.select-options');
        this.$selectOptionsInputs = $('.select-options-inputs');
        this.$selectDefault = $('.select-default');
    },
    init: function () {
        this.initVariables();
        this.registerEvents();
        this.initData();
    },
    initData() {
        if (typeof selectOptions !== 'undefined') {
            for (var option in selectOptions) {
                $('option[data-id="' + option + '"]').val(selectOptions[option].value);
                this.$selectOptionsInputs.find('input[name="type_configuration[select][select-option][' + option + ']"]').val(selectOptions[option].value);
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
            var $wrapper = $(item).closest('.select-lang-wrapper');
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
    changeAddButtonState(isEdit) {
        var $addEditButton = $('.add-edit-option-select');
        if (isEdit) {
            $addEditButton.find('.button-text-add-option').addClass('hidden');
            $addEditButton.find('.button-text-edit-option').removeClass('hidden');
        } else {
            $addEditButton.find('.button-text-add-option').removeClass('hidden');
            $addEditButton.find('.button-text-edit-option').addClass('hidden');
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
                $hiddenInput = $('input[name="type_configuration[select][select-option][' + self.currentObject + ']"]');
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
            $hiddenInput.attr('name', 'type_configuration[select][select-option][' + id + ']');
            $hiddenInput.attr('value', JSON.stringify(option.data));

            if (!self.isEditing) {
                self.$selectOptionsInputs.append($hiddenInput);
                self.$selectOptions.append($optionElement);
                self.$selectDefault.find('select').append($defaultElement);
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
            var $selected = $('.select-options option:selected');
            if ($selected.length === 0) {
                return;
            }

            var data = JSON.parse($selected.val());
            $('.select-lang-wrapper').each(function (index, item) {
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
            var $selected = $('.select-options option:selected');
            if ($selected.length === 0) {
                return;
            }
            var $hiddenInput = self.$selectOptionsInputs.find('input[name="type_configuration[select][select-option][' + $selected.attr('data-id') + ']"]');
            var $chosenOption = self.$selectDefault.find('option[data-id="' + $selected.attr('data-id') + '"]');
            $hiddenInput.remove();
            $selected.remove();
            $chosenOption.remove();
            $('.chosen').trigger('chosen:updated');
        });
    },
};

$(function () {
    selectInputProcessor.init();
});