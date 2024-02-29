/**
 * Copyright © Kellton, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'Magento_Ui/js/form/components/button',
    'jquery',
    'mage/url',
    'Magento_Ui/js/modal/alert'
], function (Button, $, urlBuilder, malert) {
    'use strict';
    
    return Button.extend({
        defaults: {
            buttonTextId: '',
            ariLabelledby: ''
        },
        initialize: function () {
            this._super();
            let isEnabled = Boolean(Number(window.isEnabled));
            let productArea = window.getAppliedArea;
            const productArray = productArea.split(',');
            if (!isEnabled || !productArray.includes("catalog-product")) {
                this.visible(false);
            }
        },

        /**
         * Get Chat Gpt API response.
         */
        contentAIGenerate: function () {
            const {index:type, ns:section} = this;
            const productTitle = $("input[name='product[name]']").val();
            if (!productTitle) {
                this.showErrorAlert('Please Fill Product Name First!');
                return false;
            }
            const productColor = $("select[name='product[color]']").find('option:selected').text();
            const productCat = $('[data-index=category_ids] div.admin__action-multiselect-wrap span.admin__action-multiselect-crumb:last-child span:first').text();
            const shortDescription = $("#product_form_short_description_ifr").contents().find('body');
            $.ajax({
                url: window.ajaxUrl,
                type: 'POST',
                showLoader: true,
                data: {
                    'form_key': FORM_KEY,
                    'form_type' : type,
                    'form_title' : productTitle,
                    'form_color' : productColor,
                    'form_cat' : productCat,
                    'section' : section
                },
                success: function (response) {
                    try {
                        const generatedContent = response.result.choices[0].message.content.split('"').join('');
                        if (type == 'meta_title_button') {
                            $("input[name='product[meta_title]']").val(generatedContent).change();
                        } else if (type == 'meta_keywords_button') {
                            $("textarea[name='product[meta_keyword]']").val(generatedContent).change();
                        } else if (type == 'meta_description_button') {
                            $("textarea[name='product[meta_description]']").val(generatedContent).change();
                        } else {
                            let descriptionContent = `<p>${generatedContent}</p>`;
                            shortDescription.html(descriptionContent).change();
                            $("#product_form_short_description").val(descriptionContent).change();
                        }
                    } catch (err) {
                        console.log(err.name,err.message);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });
        },

        /**
         * Get alert popup for Error.
         *
         * @param {String} contentItem
         * @returns {String}
         */
        showErrorAlert: (contentItem) => {
            malert({
                title: $.mage.__('Error'),
                content: $.mage.__('%1').replace('%1', contentItem),
                clickableOverlay: false,
                actions: {
                    always: function () {}
                }
            });
        }
    })
})