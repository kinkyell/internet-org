define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var Cookies = require('cookies');
    var vex = require('vex');
    var dialog = require('vex-dialog');
    var $ = require('jquery');

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

        /**
         * Modal text.
         * @enum {string}
         */
        this._TEXT = {
            prompt: window.SETTINGS.ENGLISH_LANGUAGE_NOTIFICATION.PROMPT_TEXT,
            cancel: window.SETTINGS.ENGLISH_LANGUAGE_NOTIFICATION.CANCEL_TEXT,
            okay: window.SETTINGS.ENGLISH_LANGUAGE_NOTIFICATION.OKAY_TEXT
        };

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

    /**
     * Has the view been enabled (once) ?
     * 
     * @static
     * @type {boolean}
     */
    EnglishContentDialogView.isEnabled = false;


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

        if (!EnglishContentDialogView.isEnabled && this.enabled_()) {
            $(document).on('click', '.js-englishContentDialog', this._handleOpenDialog);
        }

        EnglishContentDialogView.isEnabled = true;
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
        var displayClass = ""; 
        console.log(event.currentTarget);
        if(($(event.currentTarget).attr("data-page")) && ($(event.currentTarget).attr("data-page")=="single")) {
            if($('.viewWindow-panel-content-blogc')) {
                displayClass = " FullPageDialog";
            } else {
                displayClass = "";
            }
        } else {
            displayClass = "";
        }
        /*
        console.log("----------------");
        console.log(event);

        console.log($(event.toElement).attr("data-story-page"));
        console.log($(event.toElement).attr("data-page"));
        console.log("----------------");
        */
        this.$vexContent = dialog.confirm({
            afterOpen: this._handleOpen,
            escapeButtonCloses: true,
            focusFirstInput: false,
            message: this._TEXT.prompt,
            className: 'vex-theme-plain dialog-confirm-english'+displayClass,
            showCloseButton: true,
            buttons: {
                YES: {
                    text: this._TEXT.okay,
                    type: 'submit',
                    className: 'btn btn-confirm' //vex-dialog-button-primary
                },
                NO: {
                    text: this._TEXT.cancel,
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
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @public
     */
    proto.onDisable = function() {
        if (EnglishContentDialogView.isEnabled) {
            $(document).off('click', '.js-englishContentDialog', this._handleOpenDialog);
        }

        EnglishContentDialogView.isEnabled = false;

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

        $vexContent.wrap('<div class="vex-content-wrap"></div>');

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
                EnglishContentDialogView.CONFIRMED_COOKIE.value, {
                    expires: EnglishContentDialogView.CONFIRMED_COOKIE.expires
                }
            );

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
