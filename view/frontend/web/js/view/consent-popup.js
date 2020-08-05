/**
 * This file is part of the Flurrybox EnhancedPrivacyPro package.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Flurrybox EnhancedPrivacyPro
 * to newer versions in the future.
 *
 * @copyright Copyright (c) 2018 Flurrybox, Ltd. (https://flurrybox.com/)
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
define([
    'jquery',
    'underscore',
    'uiComponent',
    'ko',
    'Magento_Customer/js/customer-data',
    'mage/storage',
    'Magento_Ui/js/modal/modal'
], function ($, _, Component, ko, customerData, storage) {
    'use strict';

    /**
     * @typedef {Object} Customer
     * @property {string} firstname
     * @property {string} fullname
     * @property {string} websiteId
     * @property {Consents} consents
     * @property {function} subscribe
     */

    /**
     * @typedef {Object} Consents
     * @property {array} codes
     * @property {int} total
     */

    return Component.extend({
        /**
         * Component properties.
         */
        isModalVisible: ko.observable(null),
        consents: ko.observableArray([]),
        customerConsents: ko.observableArray([]),
        modalElement: $('#customer-consents-popup'),
        consentsLoaded: ko.observable(false),

        /**
         * Initialize component.
         */
        initialize: function () {
            this._super();

            var dataObject = customerData.get('customer');

            /** @type {Customer} */
            var customer = dataObject();

            if (_.isEmpty(customer.consents)) {
                dataObject.subscribe(function (customer) {
                    this.bootstrapModal(customer);
                }.bind(this));
            } else {
                this.bootstrapModal(customer);
            }
        },

        /**
         * Save consent state and initialize modal.
         *
         * @param {Customer} customer
         */
        bootstrapModal: function (customer) {
            /**
             * Wait for consent to be loaded before showing modal.
             */
            this.consentsLoaded.subscribe(function () {
                this.isModalVisible(
                    Object.keys(customer.consents.codes).length !== customer.consents.total &&
                    customer.consents.total > 0
                );
                this.setConsents(customer.consents.codes);
                this.prepareConsents();

                this.modalElement.modal({
                    responsive: true,
                    innerScroll: true,
                    modalClass: 'consent-popup-container',
                    buttons: [{
                        text: $.mage.__('Apply and continue'),
                        class: '',
                        click: function() {
                            this.closeModal();
                        }
                    }],
                    autoOpen: this.isModalVisible(),
                    title: 'Manage Consents',
                    closed: this.onClose.bind(this)
                });
            }.bind(this));

            if (_.isEmpty(customerData.get('consents')())) {
                customerData.reload(['consents'], null);

                customerData.get('consents').subscribe(function () {
                    this.consentsLoaded(true);
                }.bind(this));
            } else {
                this.consentsLoaded(true);
            }
        },

        /**
         * Parse and set consents status.
         *
         * @param consents
         */
        setConsents: function (consents) {
            var data = {};

            $.each(consents, function (id, consent) {
                data[consent.code] = !!parseInt(consents.isAllowed);
            });

            this.customerConsents(data);
        },

        /**
         * Prepare consents for rendering.
         *
         * @returns {array}
         */
        prepareConsents: function () {
            var data = customerData.get('consents')(),
                consents;

            delete data['data_id'];

            consents = Object.keys(data).map(function (key) {
                var tmpData = data[key],
                    code = tmpData.code;

                tmpData['isAllowed'] = ko.observable(
                    _.isUndefined(this.customerConsents()[code]) ? true : !!this.customerConsents()[code]
                );

                return tmpData;
            }.bind(this));

            this.consents(consents);
        },

        /**
         * Save customer consents on modal close.
         */
        onClose: function () {
            var payloadData = {};

            $.each(this.consents(), function (id, consent) {
                payloadData[consent.code] = consent.isAllowed();
            });

            var serviceUrl = 'rest/V1/customer/consents/update',
                payload = {
                    data: payloadData
                };

            storage.post(serviceUrl, JSON.stringify(payload));
        },

        /**
         * Change consent state.
         *
         * @param consent
         */
        changeConsent: function (consent) {
            consent.isAllowed(!consent.isAllowed());
        }
    });
});
