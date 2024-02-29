/**
 * Copyright © Kellton, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* global MediabrowserUtility, widgetTools, MagentovariablePlugin */
define([
    'jquery',
    'Magento_Ui/js/modal/alert'
], function ($, malert) {
    'use strict';
    
    const htmlCodeMixinModified = {
        defaults: {
            editProductPageSelectorModified: 'catalog-product-edit',
            newProductPageSelectorModified: 'catalog-product-new',
            editCategoryPageSelectorModified: 'catalog-category-edit',
            newCategoryPageSelectorModified: 'catalog-category-add',
            editCmsPageSelectorModified: 'cms-page-edit',
            newCmsPageSelectorModified: 'cms-page-add',
            wysiwigEditorSelectorModified: '.admin__control-wysiwig',
        },

        /**
         * Get category page is loading or not.
         *
         * @returns {Boolean}
         */
        _isCategoryPage : function () {
            let categoryArea = window.getAppliedArea;
            const categoryArray = categoryArea.split(',');
            let isCategoryNewPage = $('body').hasClass(this.newCategoryPageSelectorModified);
            let isCategoryEditPage = $('body').hasClass(this.editCategoryPageSelectorModified);
            if (categoryArray.includes("catalog-category") && (isCategoryNewPage || isCategoryEditPage)) {
                return true;
            }
        },

        /**
         * Get product page is loading or not.
         *
         * @returns {Boolean}
         */
        _isProductPage : function () {
            let productArea = window.getAppliedArea;
            const productArray = productArea.split(',');
            let isProductEditPage = $('body').hasClass(this.editProductPageSelectorModified);
            let isProductNewPage = $('body').hasClass(this.newProductPageSelectorModified);
            if (productArray.includes("catalog-product") && (isProductEditPage || isProductNewPage)) {
                return true;
            }
        },

        /**
         * Get cms page is loading or not.
         *
         * @returns {Boolean}
         */
        _isCmsPage : function () {
            let cmsArea = window.getAppliedArea;
            const cmsArray = cmsArea.split(',');
            let isCmsEditPage = $('body').hasClass(this.editCmsPageSelectorModified);
            let isCmsNewPage = $('body').hasClass(this.newCmsPageSelectorModified);
            if (cmsArray.includes("cms-pages") && (isCmsEditPage || isCmsNewPage)) {
                return true;
            }
        },

        /**
         * Check button config.
         *
         * @returns {Boolean}
         */
        getButtonVisibleStatus: function () {
            var isEnabled = Boolean(Number(window.isEnabled)),
                isProductEditPage = $('body').hasClass(this.editProductPageSelectorModified),
                isProductNewPage = $('body').hasClass(this.newProductPageSelectorModified),
                isCategoryNewPage = $('body').hasClass(this.newCategoryPageSelectorModified),
                isCategoryEditPage = $('body').hasClass(this.editCategoryPageSelectorModified);
            if (isEnabled && (this._isCategoryPage() || this._isProductPage() || this._isCmsPage())) {
                return true;
            }
            return false;
        },

        /**
         * Get Chat Gpt API response.
         */
        contentAIGenerate: function (data, event) {
            var errorMessgae = '';
            var pageTitle = '';
            var productColor = '';
            var productCat = '';
            var pageName = '';

            if (this._isCategoryPage()) {
                errorMessgae = 'Please Fill Category Name First!';
                pageTitle = $("input[name='name']").val();
                pageName = 'category_form';
            }
            if (this._isProductPage()) {
                errorMessgae = 'Please Fill Product Name First!';
                pageName = 'product_form';
                pageTitle = $("input[name='product[name]']").val();
                productColor = $("select[name='product[color]']").find('option:selected').text();
                productCat = $('[data-index=category_ids] div.admin__action-multiselect-wrap span.admin__action-multiselect-crumb:last-child span:first').text();
            }
            if (this._isCmsPage()) {
                errorMessgae = 'Please Fill Cms Title First!';
                pageTitle = $("input[name='title']").val();
                pageName = 'cms_form';
            }
            const type = 'description_button';
            if (!pageTitle) {
                this.showErrorAlert(errorMessgae);
                return false;
            }
            $.ajax({
                url: window.ajaxUrl,
                type: 'POST',
                showLoader: true,
                data: {
                    'form_key': FORM_KEY,
                    'form_type' : type,
                    'form_title' : pageTitle,
                    'form_color' : productColor,
                    'form_cat' : productCat,
                    'section' : pageName
                },
                success: function (response) {
                    try {
                        const generatedContent = response.result.choices[0].message.content.split('"').join('');
                        var targetField = event.currentTarget;
                        var descriptionField = $(targetField).parents(this.wysiwigEditorSelectorModified).next('textarea');
                        descriptionField.val(generatedContent).change();
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
        },
    };

    return function (target) {
        return target.extend(htmlCodeMixinModified);
    };
});
