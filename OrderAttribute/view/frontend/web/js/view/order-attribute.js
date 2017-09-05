define([
    'jquery',
    'ko',
    'uiComponent'
], function ($, ko, Component) {
    'use strict';
    var test = window.testVar;
    var test2 = window.testVar2;
    return Component.extend({
        defaults: {
            template: 'Magenest_OrderAttribute/order-attribute'
        },
        initialize: function () {
            this._super();


            ko.bindingHandlers.datetimepicker = {
                init: function (element, valueAccessor, allBindingsAccessor) {
                    return 0;
                },
                update: function (element, valueAccessor) {
                    //when the view model is updated, update the widget
                    if (1) {
                        return 0;
                    }
                }
            };

            return this;
        }
    });

});
