define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var Cookies = require('cookies');
    var $ = require('jquery');
    var vex = require('vex');
    var dialog = require('vex-dialog');

    /**
     * Prompt user to verify they would like to browse content in English
     * before loading content.
     *
     * @class EnglishContentDialogView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var EnglishContentDialogView = function($element) {
        this.destinationUrl = null;
        this.$vexContent = null;
        this._previousFocus = null;

        AbstractView.call(this, $element);
    };

    /**
     * Cookie data to be set once user confirms an English link.
     * 
     * @enum
     */
    EnglishContentDialogView.CONFIRMED_COOKIE = {
        name: 'iorg-english-content-dialog-confirmed',
        value: 'true',
        expires: 1 /*days*/
    };

    var proto = AbstractView.createChild(EnglishContentDialogView);

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {EnglishContentDialogView}
     * @private
     */
    proto.setupHandlers = function() {
        this._handleOpenDialog = this._openDialog.bind(this);
        this._handleOpen = this._onOpen.bind(this);
        this._handleClose = this._onClose.bind(this);
    };

    /**
     * Enable this component?
     * 
     * @return {Boolaen}
     * @private
     */
    proto.enabled_ = function() {
        var val = Cookies.get(EnglishContentDialogView.CONFIRMED_COOKIE.name),
            enabled = val !== EnglishContentDialogView.CONFIRMED_COOKIE.value;

        return enabled;
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @public
     */
    proto.onEnable = function() {
        this.destinationUrl = this.$element.attr('href');

        if (this.enabled_()) {
            this.$element.on('click', this._handleOpenDialog);
        }
    };

    /**
     * Open confirmation dialog.
     *
     * @method  _openDialog
     * @param  {jQuery.Event} event
     * @private
     */
    proto._openDialog = function(event) {
        event.preventDefault();

        this.$vexContent = dialog.confirm({
            afterOpen: this._handleOpen,
            escapeButtonCloses: true,
            focusFirstInput: false,
            message: 'You are now entering a section of the site that is in English only.',
            className: 'vex-theme-plain dialog-confirm-english',
            showCloseButton: true,
            buttons: {
                YES: {
                    text: 'Okay',
                    type: 'submit',
                    className: 'btn btn-confirm' //vex-dialog-button-primary
                },
                NO: {
                    text: 'Cancel',
                    type: 'button',
                    className: 'btn btn-cancel', //vex-dialog-button-secondary
                    click: function($vexContent, event) {
                        $vexContent.data().vex.value = false;
                        return vex.close($vexContent.data().vex.id);
                    }
                }
            },
            callback: this._handleClose
        });
    }

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @public
     */
    proto.onDisable = function() {
        if (this.$vexContent && this.$vexContent.data().vex) {
            vex.close(this.$vexContent.data().vex.id);
        }
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Attaches events on lightbox open
     *
     * @method _onOpen
     * @private
     */
    proto._onOpen = function($vexContent, event) {
        this._previousFocus = document.activeElement;

        // disable button outlines on click
        $vexContent.find('button,input[type="button"],input[type="submit"]').on('mousedown', function(event) {
            event.preventDefault();
        });
    };

    /**
     * Detaches events on close
     *
     * @method _onClose
     * @private
     */
    proto._onClose = function(value) {
        if (value) {
            Cookies.set(EnglishContentDialogView.CONFIRMED_COOKIE.name,
                EnglishContentDialogView.CONFIRMED_COOKIE.value, { expires: EnglishContentDialogView.CONFIRMED_COOKIE.expires });

            // TODO: How to call Router to change state manually?
            window.location = this.destinationUrl;
        }

        if (this._previousFocus) {
            this._previousFocus.focus();
        }
    };

    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////






    module.exports = EnglishContentDialogView;

});
