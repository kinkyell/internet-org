define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var eventHub = require('services/eventHub');
    var $ = require('jquery');

    /**
     * A view for updating the language attribute
     *
     * @class LanguageView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var LanguageView = function($element, selectView) {
        AbstractView.call(this, $element);
        this.selectView = selectView;
    };

    var proto = AbstractView.createChild(LanguageView);

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {LanguageView}
     * @private
     */
    proto.setupHandlers = function() {
        this._handleChange = this._onChange.bind(this);
        this._handlePageChange = this._onPageChange.bind(this);
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @returns {LanguageView}
     * @public
     */
    proto.onEnable = function() {
        eventHub.subscribe('SelectView:change', this._handleChange);
        eventHub.subscribe('viewWindow:pageLoad', this._handlePageChange);
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @returns {LanguageView}
     * @public
     */
    proto.onDisable = function() {
        eventHub.unsubscribe('SelectView:change', this._handleChange);
        eventHub.unsubscribe('viewWindow:pageLoad', this._handlePageChange);
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Change lang attribute on change
     *
     * @method _onChange
     * @param {HTMLSelectElement} select Language switcher select
     * @param {String} value Country code
     * @fires LanguageView:change
     * @private
     */
    proto._onChange = function(select, value) {
        if (select !== this.selectView.element) {
            return;
        }

        document.location = value;

        // var opt = select.options[select.selectedIndex];
        // var isRtl = (opt.getAttribute('data-dir') === 'rtl');
        // this._setLanguage(value, isRtl);
    };

    /**
     * Change page changing
     *
     * @method _onPageChange
     * @param {HTMLSelectElement} select Language switcher select
     * @param {String} value Country code
     * @fires LanguageView:change
     * @private
     */
    proto._onPageChange = function(parsedPage) {
        var select = parsedPage.reduce(function(found, el) {
            if (el.id === 'mainNav') {
                return $(el).find('#js-LanguageView');
            }
            return found;
        }, []);

        if (select.length) {
            this.selectView.updateElement(select);
        }
    };

    /**
     * Set the new language
     *
     * @method _setLanguage
     * @param {String} value Country code
     * @param {Boolean} isRtl Flag for right to left rendering
     * @private
     */
    proto._setLanguage = function(value, isRtl) {
        document.documentElement.setAttribute('lang', value);
        document.documentElement.setAttribute('dir', isRtl ? 'rtl' : 'ltr');
        eventHub.publish('LanguageView:change', value, !!isRtl);
    };


    module.exports = LanguageView;

});
