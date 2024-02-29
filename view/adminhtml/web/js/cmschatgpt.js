/**
 * Copyright © Kellton, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'Magento_Ui/js/form/components/button',
    'jquery',
    'Magento_Ui/js/modal/alert'
], function (Button, $, malert) {
    'use strict';

    return Button.extend({
        initialize: function () {
            this._super();
            let isEnabled = Boolean(Number(window.isEnabled));
            let cmsArea = window.getAppliedArea;
            const cmsArray = cmsArea.split(',');
            if (!isEnabled || !cmsArray.includes("cms-pages")) {
                this.visible(false);
            }
        },
        
        /**
         * Get Chat Gpt API response.
         */
        action: function () {
            const {index:type, ns:section} = this;
            const cmsTitle = $("input[name='title']").val();
            if (!cmsTitle) {
                this.showErrorAlert('Please Fill Cms Title First!');
                return false;
            }
            $.ajax({
                url: window.ajaxUrl,
                type: 'POST',
                showLoader: true,
                data: {
                    'form_key': FORM_KEY,
                    'form_type' : type,
                    'form_title' : cmsTitle,
                    'section' : section
                },
                success: function (response) {
                    try {
                        const generatedContent = response.result.choices[0].message.content.split('"').join('');
                        if (type == 'meta_title_button') {
                            $("input[name='meta_title']").val(generatedContent).change();

                        } else if (type == 'meta_keywords_button') {
                            $("textarea[name='meta_keywords']").val(generatedContent).change();
                        } else if (type == 'meta_description_button') {
                            $("textarea[name='meta_description']").val(generatedContent).change();
                        } else {
                            
                            let descriptionContent = `<p>${generatedContent}</p>`;
                            console.log(generatedContent);
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