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

$(document).ready(function () {
    var acftinySetup = totcfTools.tinySetupGenerator();
    acftinySetup();

    var onLoadDisplaySubformFunctions = {};

    /****************** New Input Form ******************/

    $('#totNewInputForm input[name="name"]').on('keyup', function (ev) {
        if (isArrowKey(ev) || $('#totNewInputForm [name="id_input"]').val()) return;
        $('#totNewInputForm input[name="code"]')
            .val(totcfTools.str2url(this.value, 'UTF-8'))
            .keyup();
    });

    $('#totNewInputForm select[name="type"]')
        .on('change', function () {
            // Hide all subforms, show only the right one
            $('#totNewInputForm .newInputSubform').hide();
            if (!this.value) return;

            $('#totNewInputForm .newInputSubform.type_' + this.value).show();
        })
        .change();

    $('#totNewInputForm select[name="display"]')
        .on('change', function () {
            if (!this.value) return;

            var code_display = this.value;
            // When changing display or object, we may have to reload the display subform
            $.post(
                '',
                {
                    ajax: true,
                    action: 'getDisplaySubform',
                    id_input: $('#totNewInputForm [name="id_input"]').val(),
                    code_display: code_display,
                    code_object: $('#totNewInputForm select[name="object"]').val()
                },
                function (data) {
                    $('#totCustomFields-display-form').html(data);
                    riot.mount('#totCustomFields-display-form .asyncMount');
                    $('#totCustomFields-display-form .asyncMount').removeClass(
                        'asyncMount'
                    );

                    // If we have a function to call for this display, try to call it
                    if (
                        typeof onLoadDisplaySubformFunctions[code_display] == 'function'
                    ) {
                        onLoadDisplaySubformFunctions[code_display]();
                    }
                  $('[data-toggle="tooltip"]').tooltip()
                }
            );
        })
        .change();

    $('#totNewInputForm select[name="object"]').on('change', function () {
        if (!this.value) return;

        var $displayOption = $('#totNewInputForm select[name="display"] :selected');
        if ($displayOption[0].value) {
            $('#totNewInputForm select[name="display"]').change();
        }

        $.post(
            '',
            {
                ajax: true,
                action: 'GetDisplayAdminHooks',
                id_input: $('#totNewInputForm [name="id_input"]').val(),
                code_object: $('#totNewInputForm select[name="object"]').val()
            },
            function (data) {
                $('#totcustomfields-display-admin-hooks-form').html(data);
                riot.mount('#totcustomfields-display-admin-hooks-form .asyncMount');
                $('#totcustomfields-display-admin-hooks-form .asyncMount').removeClass(
                    'asyncMount'
                );
                $('[data-toggle="tooltip"]').tooltip()
            }
        );
    }).change();

    /****************** Inputs subforms ******************/

    // Image
    $('#totNewInputForm select[name="type_configuration[image][size_type]"]')
        .on('change', function () {
            $('#totNewInputForm [id|="totNewInputSubform-image_sizetype"]').hide();
            $('#totNewInputSubform-image_sizetype-' + this.value).show();
        })
        .change();

    $('#totNewInputSubform-image_default-value')
        .on('fileAdded', function () {
            $(this)
                .find('.img_default')
                .hide();
        })
        .on('inputReset', function () {
            $(this)
                .find('.img_default')
                .show();
        });

    // Textarea
    $('#totNewInputForm select[name="type_configuration[textarea][format]"]').on(
        'change',
        function () {
            var languages = window.totcustomfields_languages;
            var editors = {};
            for (var lang of languages) {
              editors[lang.id_lang] = typeof tinyMCE === 'undefined'
                ? false
                : tinyMCE.get(`type_configuration[textarea][default_value][${lang.id_lang}]`);
            }

            switch (this.value) {
                case 'text':
                  for (var [idLang, editor] of Object.entries(editors)) {
                    if (editor) {
                      editor.save();
                      editor.remove();
                    }
                    var textArea = $(`textarea[name="type_configuration[textarea][default_value][${idLang}]"]`);
                    if (textArea.length > 0) {
                      var prettyValue = textArea.val().replace(/(<([^>]+)>)/gi, "").trim();
                      textArea.val(prettyValue);
                    }
                  }

                  break;
                case 'html':
                  for (var [idLang, editor] of Object.entries(editors)) {
                    if (editor) {
                      editor.save();
                      editor.remove();
                    }
                  }

                  break;
                case 'wysiwyg':
                  for (var [idLang, editor] of Object.entries(editors)) {
                    if (!editor) {
                      tinySetup({
                        selector:
                          `textarea[name="type_configuration[textarea][default_value][${idLang}]"]`,
                        allow_script_urls: true
                      });
                    }
                  }
                default:
                    break;
            }
        }
    );

    // Dirty, but that's what PS does, and there's apparently no better solution
    // > Maybe we can provide our own 'tinyMCE setup', and thus be able to fire
    // > an event when tinyMCE is properly loaded.
    (function totcustomfields_tinyMCESetup() {
        // We want to make sure tinyMCE is loaded before we do anything.
        if (typeof tinyMCE === 'undefined') {
            setTimeout(function () {
                totcustomfields_tinyMCESetup();
            }, 100);
        } else {
            $('select[name="type_configuration[textarea][format]"]').trigger(
                'change'
            );
        }
    })();

    /****************** Displays subforms ******************/

    onLoadDisplaySubformFunctions['smarty'] = function () {
        var $inputCodeField = $('#totNewInputForm input[name="code"]');
        if (!$inputCodeField.hasClass('smarty-handler')) {
            $inputCodeField
                .on('keyup', function (ev) {
                    if (isArrowKey(ev)) return;

                    $('#totCustomFields-smarty-shortcode').val(
                        getSmartyShortCode(this.value)
                    );
                })
                .addClass('smarty-handler');
        }

        if (!$('#totCustomFields-smarty-shortcode').val()) {
            $inputCodeField.keyup();
        }
    };

    function getSmartyShortCode(code) {
        if (PS_VERSION >= '1.7') {
            return '{$totcustomfields_display_' + code + ' nofilter}';
        }

        return '{$totcustomfields_display_' + code + "|escape:'quotes':'UTF-8'}";
    }

    onLoadDisplaySubformFunctions['widget'] = function () {
        var $inputCodeField = $('#totNewInputForm input[name="code"]');
        if (!$inputCodeField.hasClass('widget-handler')) {
            $inputCodeField
                .on('keyup', function (ev) {
                    if (isArrowKey(ev)) return;

                    $('#totCustomFields-widget-shortcode').val(
                        getWidgetShortCode(this.value)
                    );
                })
                .addClass('widget-handler');
        }

        if (!$('#totCustomFields-widget-shortcode').val()) {
            $inputCodeField.keyup();
        }
    };

    function getWidgetShortCode(code) {
        return '{widget name="totcustomfields" code="' + code + '" action="renderCustomFields"}';
    }

    /****************** Objects pages ******************/

    $('.objectDisplayForm')
        .on('click', '.toggleActiveDisplay a', function (event) {
            event.stopPropagation();
            event.preventDefault();

            var $self = $(this);
            if ($self.data('submitting')) return;

            $self.data('submitting', true);

            var id_input = this.href.replace(/(.*#)/, '');
            if (!id_input) return;

            $.post(
                '',
                {
                    ajax: true,
                    action: 'toggleActiveDisplay',
                    id_input: id_input
                },
                function (data) {
                    if (data == 'success') {
                        $('[href="#' + id_input + '"] .status').each(function (key, elem) {
                            if ($(elem).hasClass('icon-check')) {
                                elem.className = elem.className.replace(
                                    'icon-check',
                                    'icon-remove'
                                );
                            } else {
                                elem.className = elem.className.replace(
                                    'icon-remove',
                                    'icon-check'
                                );
                            }
                        });
                    } else {
                        alert('Could not update status.');
                    }

                    $self.data('submitting', false);
                }
            );
        })
        .on('click', '.deleteInputAction', function (ev) {
            return confirm(totcustomfields_deleteInput_confirmation);
        });

    /****************** Advanced setting page ******************/

    $('.switch-multiple-product-tabs').change(function (ev) {
        if (ev.srcElement.value == 1) {
            $("#totCustomFields-product-tab-title").hide();
        } else {
            $("#totCustomFields-product-tab-title").show();
        }
    });
});

document.addEventListener('mountPsTags', function (event) {
  var configurations = document.querySelector('.totcustomfields-configuration-wrapper');
  configurations.classList.remove('loading');
  configurations.classList.add('loaded');
});