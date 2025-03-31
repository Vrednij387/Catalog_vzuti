/**
 * Description of prestui.js
 *
 * @version 1.0
 * @author    202-ecommerce
 * @copyright 202-ecommerce
 * @license    202-ecommerce
 */

Array.prototype.last = function () {
    return this[this.length - 1];
};

var psInputFile = {
    init: function () {
        var self = this;

        $('ps-input-file-core button.file-add-button').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var el = self.getElementsInScope(this);

            $(el.inputFile).trigger('click');

            self.cleanSuccess(el);
            self.cleanError(el);
        });

        $('ps-input-file-core button.file-remove-button').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            self.resetFileUpload(this);
        });

        $('ps-input-file-core input[type=file]').on('change', function (e) {
            var el = self.getElementsInScope(this);
            self.cleanError(el);

            var file = self.getFile(this);
            if (self.isCorrectExtension(this)) {
                // handle success part
                self.addSuccess(this, file.last(), el);
                el.buttonAdd.hide();
                el.success.show();
                $(el.inputFile).trigger('fileAdded');
            } else {
                // handle error part
                self.resetFileUpload(this);
                self.addError(this, file.last(), el);
                el.error.show();
            }
        });
    },

    getFile: function (that) {
        return $(that).val().split(/[\\/]/);
    },

    isCorrectExtension: function (that) {
        var self = this;

        var file = self.getFile(that);
        var extension = file.last().split('.');
        var dataFileType = $(that).attr('data-file-type');
        var extensionIsAllowed = 0;
        if (dataFileType && dataFileType.length > 0) {
            var allowedExtension = $(that).attr('data-file-type').split(',');
            if (allowedExtension.length > 0) {
                $.each(allowedExtension, function (index, value) {
                    allowedExtension[index] = value.toLowerCase().replace(/\s+/g, '');
                });
                extensionIsAllowed = $.inArray(extension.last().toLowerCase(), allowedExtension);
            }
        }

        return (extensionIsAllowed >= 0);
    },

    addSuccess: function (that, file, el) {
        $(el.success).find('.file-selected-filename').html('File ' + file + ' correctly selected');
    },

    cleanSuccess: function (el) {
        $(el.success).find('.file-selected-filename').empty();
        $(el.success).hide();
    },

    addError: function (that, file, el) {
        $(el.error).html('<strong>' + file + '</strong> : File type not allowed');
    },

    cleanError: function (el) {
        $(el.error).empty();
        $(el.error).hide();
    },

    resetFileUpload: function (that) {
        var self = this;
        var el = self.getElementsInScope(that);

        self.cleanSuccess(el);
        self.cleanError(el);
        $(el.inputFile).val('');
        $(el.buttonAdd).show();

        $(el.inputFile).trigger('inputReset');
    },

    getElementsInScope: function (that) {
        var inputFileCoreElement = $(that).parents('ps-input-file-core');
        var inputFileElement = $(inputFileCoreElement).find('input[type=file]');
        var buttonAddElement = $(inputFileCoreElement).find('button.file-add-button');
        var successElement = $(inputFileCoreElement).find('.file-selected');
        var errorElement = $(inputFileCoreElement).find('.file-errors');

        return {
            inputFileCore: inputFileCoreElement,
            inputFile: inputFileElement,
            buttonAdd: buttonAddElement,
            success: successElement,
            error: errorElement
        };
    }

};

var psInputFileDeletable = {
    init: function () {
        var self = this;

        $('ps-input-file-deletable .file-delete-button').click(function () {
            var el = self.getElementsInScope(this);
            $(el.inputFile).val('');
            $(el.checkboxDelete).prop('checked', true);
            $(el.success).find('.file-selected-filename').html('Current file will be deleted.');
            el.success.show();
            el.buttonAdd.hide();
            el.buttonDelete.hide();
        });

        $('ps-input-file-deletable ps-input-file-core').on('fileAdded', function () {
            var el = self.getElementsInScope(this);
            el.buttonDelete.hide();
        }).on('inputReset', function () {
            var el = self.getElementsInScope(this);
            el.buttonDelete.show();
            $(el.checkboxDelete).prop('checked', false);
        });
    },
    getElementsInScope: function (that) {
        var inputFileDeletable = $(that).parents('ps-input-file-deletable');
        var buttonDelete = $(inputFileDeletable).find(".file-delete-button");
        var checkboxDelete = $(inputFileDeletable).find(".file-delete-checkbox");

        var el = psInputFile.getElementsInScope(buttonDelete);
        el.buttonDelete = buttonDelete;
        el.checkboxDelete = checkboxDelete;
        return el;
    }
};

$(document).ready(function () {
    psInputFile.init();
    psInputFileDeletable.init();
});
