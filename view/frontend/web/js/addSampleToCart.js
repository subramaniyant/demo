/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/translate',
    'jquery/ui'
], function ($, $t) {
    'use strict';

    $.widget('mage.addSampleToCart', {
        options: {
            processStart: null,
            processStop: null,
            bindSubmit: true,
            minicartSelector: '[data-block="minicart"]',
            messagesSelector: '[data-placeholder="messages"]',
            productStatusSelector: '.stock.available',
            addToCartButtonSelector: '.action.tocart',
            addToCartButtonDisabledClass: 'disabled',
            addToCartButtonTextWhileAdding: 'Adding ..',
            addToCartButtonTextAdded: 'Added',
            addToCartButtonTextDefault: 'Add to Cart',
            sampleAddToCartButtonSelector: '.sample-addtocart',
            sampleAddToCartButtonDisabledClass: 'disabled',
            sampleAddToCartButtonTextWhileAdding: 'Adding Sample ..',
            sampleAddToCartButtonTextAdded: 'Sample Added',
            sampleAddToCartButtonTextDefault: 'Add Sample to Cart'
        },

        /** @inheritdoc */
        _create: function () {
            if (this.options.bindSubmit) {
                this._bindSubmit();
            }
        },

        /**
         * @private
         */
        _bindSubmit: function () {
            var self = this;

            this.element.on('submit', function (e) {
                e.preventDefault();
                self.submitForm($(this));
            });
        },

        /**
         * @return {Boolean}
         */
        isLoaderEnabled: function () {
            return this.options.processStart && this.options.processStop;
        },

        /**
         * Handler for the form 'submit' event
         *
         * @param {Object} form
         */
        submitForm: function (form) {
            var addToCartButton, self = this;

            if (form.has('input[type="file"]').length && form.find('input[type="file"]').val() !== '') {
                self.element.off('submit');
                // disable 'Add to Cart' button
                addToCartButton = $(form).find(this.options.addToCartButtonSelector);
                addToCartButton.prop('disabled', true);
                addToCartButton.addClass(this.options.addToCartButtonDisabledClass);
                form.submit();
            } else {
                self.ajaxSubmit(form);
            }
        },

        /**
         * @param {String} form
         */
        ajaxSubmit: function (form) {
            var self = this;

            $(self.options.minicartSelector).trigger('contentLoading');
            //if(!form.find('.sample_product').val())
            self.disableAddToCartButton(form);

            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                type: 'post',
                dataType: 'json',

                /** @inheritdoc */
                beforeSend: function () {
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStart);
                    }
                },

                /** @inheritdoc */
                success: function (res) {
                    var eventData, parameters;

                    $(document).trigger('ajax:addToCart', form.data().productSku);

                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStop);
                    }

                    if (res.backUrl) {
                        eventData = {
                            'form': form,
                            'redirectParameters': []
                        };
                        // trigger global event, so other modules will be able add parameters to redirect url
                        $('body').trigger('catalogCategoryAddToCartRedirect', eventData);

                        if (eventData.redirectParameters.length > 0) {
                            parameters = res.backUrl.split('#');
                            parameters.push(eventData.redirectParameters.join('&'));
                            res.backUrl = parameters.join('?');
                        }
                        window.location = res.backUrl;

                        return;
                    }

                    if (res.messages) {
                        $(self.options.messagesSelector).html(res.messages);
                    }

                    if (res.minicart) {
                        $(self.options.minicartSelector).replaceWith(res.minicart);
                        $(self.options.minicartSelector).trigger('contentUpdated');
                    }

                    if (res.product && res.product.statusText) {
                        $(self.options.productStatusSelector)
                            .removeClass('available')
                            .addClass('unavailable')
                            .find('span')
                            .html(res.product.statusText);
                    }
                    self.enableAddToCartButton(form);

                    form.find('.sample_product').val(0);


                }
            });

        },

        /**
         * @param {String} form
         */
        disableAddToCartButton: function (form) {
            if(form.find('.sample_product').val() == 1)
            {
                var sampleAddToCartButtonTextWhileAdding = this.options.sampleAddToCartButtonTextWhileAdding || $t('Adding...'),
                    sampleAddToCartButton = $(form).find(this.options.sampleAddToCartButtonSelector);


                sampleAddToCartButton.addClass(this.options.sampleAddToCartButtonDisabledClass);
                sampleAddToCartButton.find('span').text(sampleAddToCartButtonTextWhileAdding);
                if(form.find('.is_pdp').val() == 1)
                {
                    if(sampleAddToCartButton.find('label').length == 0)
                    {
                        sampleAddToCartButton.append('<br/><label>'+ form.find('.label_value').val() +'</label>');
                    }
                }
                sampleAddToCartButton.attr('title', sampleAddToCartButtonTextWhileAdding);
            }
            else {
                var addToCartButtonTextWhileAdding = this.options.addToCartButtonTextWhileAdding || $t('Adding...'),
                    addToCartButton = $(form).find(this.options.addToCartButtonSelector);


                addToCartButton.addClass(this.options.addToCartButtonDisabledClass);
                addToCartButton.find('span').text(addToCartButtonTextWhileAdding);
                addToCartButton.attr('title', addToCartButtonTextWhileAdding);
            }
        },

        /**
         * @param {String} form
         */
        enableAddToCartButton: function (form) {
            if (form.find('.sample_product').val() == 1)
            {
                    var addToCartButtonTextAdded = this.options.sampleAddToCartButtonTextAdded || $t('Added Sample'),
                    self = this,
                    addToCartButton = $(form).find(this.options.sampleAddToCartButtonSelector);

                    addToCartButton.find('span').text(addToCartButtonTextAdded);
                    if(form.find('.is_pdp').val() == 1)
                    {
                        if(addToCartButton.find('label').length == 0)
                        {
                            addToCartButton.append('<br/><label>'+ form.find('.label_value').val() +'</label>');
                        }
                    }
                    addToCartButton.attr('title', addToCartButtonTextAdded);

                setTimeout(function () {
                    var addToCartButtonTextDefault = self.options.sampleAddToCartButtonTextDefault || $t('Add Sample to Cart');

                    addToCartButton.removeClass(self.options.sampleAddToCartButtonDisabledClass);
                    addToCartButton.find('span').text(addToCartButtonTextDefault);

                    if(form.find('.is_pdp').val() == 1)
                    {
                        if(addToCartButton.find('label').length == 0)
                        {
                            addToCartButton.append('<br/><label>'+ form.find('.label_value').val() +'</label>');
                        }
                    }
                    addToCartButton.attr('title', addToCartButtonTextDefault);
                }, 1000);
             }
            else{
                var addToCartButtonTextAdded = this.options.addToCartButtonTextAdded || $t('Added'),
                    self = this,
                    addToCartButton = $(form).find(this.options.addToCartButtonSelector);

                addToCartButton.find('span').text(addToCartButtonTextAdded);
                addToCartButton.attr('title', addToCartButtonTextAdded);

                setTimeout(function () {
                    var addToCartButtonTextDefault = self.options.addToCartButtonTextDefault || $t('Add to Cart');

                    addToCartButton.removeClass(self.options.addToCartButtonDisabledClass);
                    addToCartButton.find('span').text(addToCartButtonTextDefault);
                    addToCartButton.attr('title', addToCartButtonTextDefault);
                }, 1000);
            }
        }

    });

    return $.mage.addSampleToCart;
});
