define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var eventHub = require('services/eventHub');

    /**
     * A view for updating the language attribute
     *
     * @class LanguageView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var LanguageView = function($element) {
        AbstractView.call(this, $element);
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
        debugger;
        if (select !== this.element) {
            return;
        }

        document.location = value;

        // var opt = select.options[select.selectedIndex];
        // var isRtl = (opt.getAttribute('data-dir') === 'rtl');
        // this._setLanguage(value, isRtl);
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
