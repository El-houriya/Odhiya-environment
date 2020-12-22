/*!
 * Variation Swatches for WooCommerce v1.1.2 
 * 
 * Author: Emran Ahmed ( emran.bd.08@gmail.com ) 
 * Date: 11/26/2020, 7:20:28 PM
 * Released under the GPLv3 license.
 */
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 9);
/******/ })
/************************************************************************/
/******/ ({

/***/ 10:
/***/ (function(module, exports, __webpack_require__) {

jQuery(function ($) {

    Promise.resolve().then(function () {
        return __webpack_require__(11);
    }).then(function () {

        // Init on Ajax Popup :)
        $(document).on('wc_variation_form.wvs', '.variations_form:not(.wvs-loaded)', function (event) {
            $(this).WooVariationSwatches();
        });

        // Support for Jetpack's Infinite Scroll,
        $(document.body).on('post-load.wvs', function () {
            $('.variations_form').each(function () {
                $(this).wc_variation_form();
            });
        });

        // Support for Yith Infinite Scroll
        $(document).on('yith_infs_added_elem.wvs', function () {
            $('.variations_form').each(function () {
                $(this).wc_variation_form();
            });
        });

        // Support for Yith Ajax Filter
        $(document).on('yith-wcan-ajax-filtered.wvs', function () {
            $('.variations_form').each(function () {
                $(this).wc_variation_form();
            });
        });

        // Support for Woodmart theme
        $(document).on('wood-images-loaded.wvs', function () {
            $('.variations_form').each(function () {
                $(this).wc_variation_form();
            });
        });

        // Support for berocket ajax filters
        $(document).on('berocket_ajax_products_loaded.wvs', function () {
            $('.variations_form').each(function () {
                $(this).wc_variation_form();
            });
        });

        // Flatsome Infinite Scroll Support
        $('.shop-container .products').on('append.infiniteScroll', function (event, response, path) {
            $('.variations_form').each(function () {
                $(this).wc_variation_form();
            });
        });

        // FacetWP Load More
        $(document).on('facetwp-loaded.wvs', function () {
            $('.variations_form').each(function () {
                $(this).wc_variation_form();
            });
        });

        // WooCommerce Filter Nav
        $('body').on('aln_reloaded.wvs', function () {
            _.delay(function () {
                $('.variations_form').each(function () {
                    $(this).wc_variation_form();
                });
            }, 100);
        });
    });
}); // end of jquery main wrapper

/***/ }),

/***/ 11:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

// ================================================================
// WooCommerce Variation Swatches
/*global _, wc_add_to_cart_variation_params, woo_variation_swatches_options */
// ================================================================

var WooVariationSwatches = function ($) {

    var Default = {};

    var WooVariationSwatches = function () {
        function WooVariationSwatches(element, config) {
            _classCallCheck(this, WooVariationSwatches);

            // Assign
            this._element = $(element);
            this._config = $.extend({}, Default, config);
            this._generated = {};
            this._out_of_stock = {};
            this._disabled = {};
            this.product_variations = this._element.data('product_variations') || [];
            this.is_ajax_variation = this.product_variations.length < 1;
            this.product_id = this._element.data('product_id');
            this.reset_variations = this._element.find('.reset_variations');
            /*this.hidden_behaviour       = $('body').hasClass('woo-variation-swatches-attribute-behavior-hide');*/
            this.is_mobile = $('body').hasClass('woo-variation-swatches-on-mobile');
            this.selected_item_template = '<span class="woo-selected-variation-item-name" data-default=""></span>';

            this._element.addClass('wvs-loaded');

            // Call
            this.init();
            this.update();

            // Trigger
            $(document).trigger('woo_variation_swatches', [this._element]);
        }

        _createClass(WooVariationSwatches, [{
            key: 'init',
            value: function init() {
                var _this2 = this;

                var _this = this;

                this._generated = this.product_variations.reduce(function (obj, variation) {

                    Object.keys(variation.attributes).map(function (attribute_name) {
                        if (!obj[attribute_name]) {
                            obj[attribute_name] = [];
                        }

                        if (variation.attributes[attribute_name]) {
                            obj[attribute_name].push(variation.attributes[attribute_name]);
                        }
                    });

                    return obj;
                }, {});

                this._out_of_stock = this.product_variations.reduce(function (obj, variation) {

                    Object.keys(variation.attributes).map(function (attribute_name) {
                        if (!obj[attribute_name]) {
                            obj[attribute_name] = [];
                        }

                        if (variation.attributes[attribute_name] && !variation.is_in_stock) {
                            obj[attribute_name].push(variation.attributes[attribute_name]);
                        }
                    });

                    return obj;
                }, {});

                // Append Selected Item Template
                if (woo_variation_swatches_options.show_variation_label) {
                    this._element.find('.variations .label').each(function (index, el) {
                        $(el).append(_this2.selected_item_template);
                    });
                }

                this._element.find('ul.variable-items-wrapper').each(function (i, el) {

                    $(this).parent().addClass('woo-variation-items-wrapper');

                    var select = $(this).siblings('select.woo-variation-raw-select');
                    var selected = '';

                    var options = $(this).siblings('select.woo-variation-raw-select').find('option');
                    var disabled = $(this).siblings('select.woo-variation-raw-select').find('option:disabled');
                    var current = $(this).siblings('select.woo-variation-raw-select').find('option:selected');
                    var eq = $(this).siblings('select.woo-variation-raw-select').find('option').eq(1);

                    var li = $(this).find('li:not(.woo-variation-swatches-variable-item-more)');
                    var reselect_clear = $(this).hasClass('reselect-clear');

                    var mouse_event_name = 'click.wvs'; // 'touchstart click';

                    var attribute = $(this).data('attribute_name');
                    var attribute_values = _this.is_ajax_variation ? [] : _this._generated[attribute];
                    var out_of_stocks = _this.is_ajax_variation ? [] : _this._out_of_stock[attribute];
                    var selects = [];
                    var disabled_selects = [];
                    var $selected_variation_item = $(this).parent().prev().find('.woo-selected-variation-item-name');

                    // For Avada FIX
                    if (options.length < 1) {
                        select = $(this).parent().find('select.woo-variation-raw-select');
                        options = $(this).parent().find('select.woo-variation-raw-select').find('option');
                        disabled = $(this).parent().find('select.woo-variation-raw-select').find('option:disabled');
                        current = $(this).parent().find('select.woo-variation-raw-select').find('option:selected');
                        eq = $(this).parent().find('select.woo-variation-raw-select').find('option').eq(1);
                    }

                    options.each(function () {
                        if ($(this).val() !== '') {
                            selects.push($(this).val());
                            selected = current ? current.val() : eq.val();
                        }
                    });

                    disabled.each(function () {
                        if ($(this).val() !== '') {
                            disabled_selects.push($(this).val());
                        }
                    });

                    var in_stocks = _.difference(selects, disabled_selects);

                    // Mark Selected
                    li.each(function (index, li) {

                        var attribute_value = $(this).attr('data-value');
                        var attribute_title = $(this).attr('data-title');

                        $(this).removeClass('selected disabled').addClass('disabled');
                        $(this).attr('aria-checked', 'false');
                        $(this).attr('tabindex', '-1');

                        if ($(this).hasClass('radio-variable-item')) {
                            $(this).find('input.wvs-radio-variable-item:radio').prop('disabled', true).prop('checked', false);
                        }

                        // Default Selected
                        // We can't use es6 includes for IE11
                        // in_stocks.includes(attribute_value)
                        // _.contains(in_stocks, attribute_value)

                        if (_.contains(in_stocks, attribute_value)) {

                            $(this).removeClass('selected disabled');
                            $(this).removeAttr('aria-hidden');
                            $(this).attr('tabindex', '0');

                            $(this).find('input.wvs-radio-variable-item:radio').prop('disabled', false);

                            if (attribute_value === selected) {

                                $(this).addClass('selected');
                                $(this).attr('aria-checked', 'true');

                                if (woo_variation_swatches_options.show_variation_label) {
                                    $selected_variation_item.text(': ' + attribute_title);
                                }

                                if ($(this).hasClass('radio-variable-item')) {
                                    $(this).find('input.wvs-radio-variable-item:radio').prop('checked', true);
                                }
                            }
                        }
                    });

                    // Trigger Select event based on list

                    if (reselect_clear) {

                        // Non Selected Item Should Select
                        $(this).on(mouse_event_name, 'li:not(.selected):not(.radio-variable-item):not(.woo-variation-swatches-variable-item-more)', function (e) {
                            e.preventDefault();
                            e.stopPropagation();
                            var value = $(this).data('value');
                            select.val(value).trigger('change');
                            select.trigger('click');

                            select.trigger('focusin');

                            if (_this.is_mobile) {
                                select.trigger('touchstart');
                            }

                            $(this).trigger('focus'); // Mobile tooltip
                            $(this).trigger('wvs-selected-item', [value, select, _this._element]); // Custom Event for li
                        });

                        // Selected Item Should Non Select
                        $(this).on(mouse_event_name, 'li.selected:not(.radio-variable-item):not(.woo-variation-swatches-variable-item-more)', function (e) {
                            e.preventDefault();
                            e.stopPropagation();

                            var value = $(this).val();

                            select.val('').trigger('change');
                            select.trigger('click');

                            select.trigger('focusin');

                            if (_this.is_mobile) {
                                select.trigger('touchstart');
                            }

                            $(this).trigger('focus'); // Mobile tooltip

                            $(this).trigger('wvs-unselected-item', [value, select, _this._element]); // Custom Event for li
                        });

                        // RADIO

                        // On Click trigger change event on Radio button
                        $(this).on(mouse_event_name, 'input.wvs-radio-variable-item:radio', function (e) {

                            e.stopPropagation();

                            $(this).trigger('change.wvs', { radioChange: true });
                        });

                        $(this).on('change.wvs', 'input.wvs-radio-variable-item:radio', function (e, params) {

                            e.preventDefault();
                            e.stopPropagation();

                            if (params && params.radioChange) {

                                var value = $(this).val();
                                var is_selected = $(this).parent('li.radio-variable-item').hasClass('selected');

                                if (is_selected) {
                                    select.val('').trigger('change');
                                    $(this).parent('li.radio-variable-item').trigger('wvs-unselected-item', [value, select, _this._element]); // Custom Event for li
                                } else {
                                    select.val(value).trigger('change');
                                    $(this).parent('li.radio-variable-item').trigger('wvs-selected-item', [value, select, _this._element]); // Custom Event for li
                                }

                                select.trigger('click');
                                select.trigger('focusin');
                                if (_this.is_mobile) {
                                    select.trigger('touchstart');
                                }
                            }
                        });
                    } else {

                        $(this).on(mouse_event_name, 'li:not(.radio-variable-item):not(.woo-variation-swatches-variable-item-more)', function (event) {

                            event.preventDefault();
                            event.stopPropagation();

                            var value = $(this).data('value');
                            select.val(value).trigger('change');
                            select.trigger('click');
                            select.trigger('focusin');
                            if (_this.is_mobile) {
                                select.trigger('touchstart');
                            }

                            $(this).trigger('focus'); // Mobile tooltip

                            $(this).trigger('wvs-selected-item', [value, select, _this._element]); // Custom Event for li
                        });

                        // Radio
                        $(this).on('change.wvs', 'input.wvs-radio-variable-item:radio', function (event) {
                            event.preventDefault();
                            event.stopPropagation();

                            var value = $(this).val();

                            select.val(value).trigger('change');
                            select.trigger('click');
                            select.trigger('focusin');

                            if (_this.is_mobile) {
                                select.trigger('touchstart');
                            }

                            // Radio
                            $(this).parent('li.radio-variable-item').removeClass('selected disabled').addClass('selected');
                            $(this).parent('li.radio-variable-item').trigger('wvs-selected-item', [value, select, _this._element]); // Custom Event for li
                        });
                    }

                    // Keyboard Access
                    $(this).on('keydown.wvs', 'li:not(.disabled):not(.woo-variation-swatches-variable-item-more)', function (event) {
                        if (event.keyCode && 32 === event.keyCode || event.key && " " === event.key || event.keyCode && 13 === event.keyCode || event.key && "enter" === event.key.toLowerCase()) {
                            event.preventDefault();
                            $(this).trigger(mouse_event_name);
                        }
                    });
                });

                this._element.trigger('woo_variation_swatches_init', [this, this.product_variations]);

                $(document).trigger('woo_variation_swatches_loaded', [this._element, this.product_variations]);
            }
        }, {
            key: 'update',
            value: function update() {

                var _this = this;

                this._element.on('woocommerce_variation_has_changed.wvs', function (event) {

                    // Don't use any propagation. It will disable composit product functionality
                    // event.stopPropagation();

                    $(this).find('ul.variable-items-wrapper').each(function (index, el) {

                        var select = $(this).siblings('select.woo-variation-raw-select');
                        var selected = '';

                        var options = $(this).siblings('select.woo-variation-raw-select').find('option');
                        var disabled = $(this).siblings('select.woo-variation-raw-select').find('option:disabled');
                        var current = $(this).siblings('select.woo-variation-raw-select').find('option:selected');
                        var eq = $(this).siblings('select.woo-variation-raw-select').find('option').eq(1);
                        var li = $(this).find('li:not(.woo-variation-swatches-variable-item-more)');

                        //let reselect_clear   = $(this).hasClass('reselect-clear');
                        //let is_mobile        = $('body').hasClass('woo-variation-swatches-on-mobile');
                        //let mouse_event_name = 'click.wvs'; // 'touchstart click';

                        var attribute = $(this).data('attribute_name');
                        var attribute_values = _this.is_ajax_variation ? [] : _this._generated[attribute];
                        var out_of_stocks = _this.is_ajax_variation ? [] : _this._out_of_stock[attribute];
                        var selects = [];
                        var disabled_selects = [];
                        var $selected_variation_item = $(this).parent().prev().find('.woo-selected-variation-item-name');

                        // For Avada FIX
                        if (options.length < 1) {
                            select = $(this).parent().find('select.woo-variation-raw-select');
                            options = $(this).parent().find('select.woo-variation-raw-select').find('option');
                            disabled = $(this).parent().find('select.woo-variation-raw-select').find('option:disabled');
                            current = $(this).parent().find('select.woo-variation-raw-select').find('option:selected');
                            eq = $(this).parent().find('select.woo-variation-raw-select').find('option').eq(1);
                        }

                        options.each(function () {
                            if ($(this).val() !== '') {
                                selects.push($(this).val());
                                selected = current ? current.val() : eq.val();
                            }
                        });

                        disabled.each(function () {
                            if ($(this).val() !== '') {
                                disabled_selects.push($(this).val());
                            }
                        });

                        var in_stocks = _.difference(selects, disabled_selects);

                        if (_this.is_ajax_variation) {

                            li.each(function (index, el) {

                                var attribute_value = $(this).attr('data-value');
                                var attribute_title = $(this).attr('data-title');

                                $(this).removeClass('selected disabled');
                                $(this).attr('aria-checked', 'false');

                                // To Prevent blink
                                if (selected.length < 1 && woo_variation_swatches_options.show_variation_label) {
                                    $selected_variation_item.text('');
                                }

                                if (attribute_value === selected) {
                                    $(this).addClass('selected');
                                    $(this).attr('aria-checked', 'true');

                                    if (woo_variation_swatches_options.show_variation_label) {
                                        $selected_variation_item.text(': ' + attribute_title);
                                    }

                                    if ($(this).hasClass('radio-variable-item')) {
                                        $(this).find('input.wvs-radio-variable-item:radio').prop('disabled', false).prop('checked', true);
                                    }
                                }

                                $(this).trigger('wvs-item-updated', [selected, attribute_value, _this]);
                            });
                        } else {

                            li.each(function (index, el) {

                                var attribute_value = $(this).attr('data-value');
                                var attribute_title = $(this).attr('data-title');

                                $(this).removeClass('selected disabled').addClass('disabled');
                                $(this).attr('aria-checked', 'false');
                                $(this).attr('tabindex', '-1');

                                if ($(this).hasClass('radio-variable-item')) {
                                    $(this).find('input.wvs-radio-variable-item:radio').prop('disabled', true).prop('checked', false);
                                }

                                // if (_.contains(selects, value))
                                // if (_.indexOf(selects, value) !== -1)
                                // if (selects.includes(value))

                                // We can't use es6 includes for IE11
                                // in_stocks.includes(attribute_value)
                                // _.contains(in_stocks, attribute_value)

                                // Make Selected // selects.includes(attribute_value) // in_stocks
                                if (_.contains(in_stocks, attribute_value)) {

                                    $(this).removeClass('selected disabled');
                                    $(this).removeAttr('aria-hidden');
                                    $(this).attr('tabindex', '0');

                                    $(this).find('input.wvs-radio-variable-item:radio').prop('disabled', false);

                                    // To Prevent blink
                                    if (selected.length < 1 && woo_variation_swatches_options.show_variation_label) {
                                        $selected_variation_item.text('');
                                    }

                                    if (attribute_value === selected) {

                                        $(this).addClass('selected');
                                        $(this).attr('aria-checked', 'true');

                                        if (woo_variation_swatches_options.show_variation_label) {
                                            $selected_variation_item.text(': ' + attribute_title);
                                        }

                                        if ($(this).hasClass('radio-variable-item')) {
                                            $(this).find('input.wvs-radio-variable-item:radio').prop('checked', true);
                                        }
                                    }
                                }

                                $(this).trigger('wvs-item-updated', [selected, attribute_value, _this]);
                            });
                        }

                        // Items Updated
                        $(this).trigger('wvs-items-updated');
                    });
                });
            }
        }], [{
            key: '_jQueryInterface',
            value: function _jQueryInterface(config) {
                return this.each(function () {
                    new WooVariationSwatches(this, config);
                });
            }
        }]);

        return WooVariationSwatches;
    }();

    /**
     * ------------------------------------------------------------------------
     * jQuery
     * ------------------------------------------------------------------------
     */

    $.fn['WooVariationSwatches'] = WooVariationSwatches._jQueryInterface;
    $.fn['WooVariationSwatches'].Constructor = WooVariationSwatches;
    $.fn['WooVariationSwatches'].noConflict = function () {
        $.fn['WooVariationSwatches'] = $.fn['WooVariationSwatches'];
        return WooVariationSwatches._jQueryInterface;
    };

    return WooVariationSwatches;
}(jQuery);

/* harmony default export */ __webpack_exports__["default"] = (WooVariationSwatches);

/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(10);


/***/ })

/******/ });