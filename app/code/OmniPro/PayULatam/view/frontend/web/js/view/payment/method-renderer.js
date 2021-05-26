define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (
    Component,
    rendererList
) {
    'use strict'
    rendererList.push(
        {
            'type': 'payulatam',
            'component': 'OmniPro_PayULatam/js/view/payment/method-renderer/payu-method'
        }
    );
    return Component.extend({});
}); 