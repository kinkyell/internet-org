define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var $ = require('jquery');

    var lastNum = 0;

    /**
     * A view to load in more content
     *
     * @class VideoModalView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var VideoModalView = function($element) {
        this.videoNum = lastNum;
        lastNum++;
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(VideoModalView);

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {VideoModalView}
     * @private
     */
    proto.setupHandlers = function() {
        this._handleOpen = this._onOpen.bind(this);
        this._handleClose = this._onClose.bind(this);
        this._handleOverlayClick = this._onOverlayClick.bind(this);
        this._handleEscKey = this._onEscKey.bind(this);
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @public
     */
    proto.onEnable = function() {
        this.$element
            .addClass('swipebox-video')
            .attr('rel', 'vimeo' + this.videoNum);

        // NOTE: Needs to be queried because it uses the 'selector' property of the jquery object
        $('[rel="vimeo'+ this.videoNum + '"]').swipebox({
            vimeoColor: 'ff6b00',
            afterOpen: this._handleOpen,
            afterClose: this._handleClose
        });
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @public
     */
    proto.onDisable = function() {
        $.swipebox.close();
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
    proto._onOpen = function() {
        $('#swipebox-overlay').on('click', this._handleOverlayClick);
        $(document.body).on('keydown', this._handleEscKey);
        this._previousFocus = document.activeElement;
        var closeBtn = $('#swipebox-close')[0];

        // create label for close btn
        var closeBtnLabel = document.createElement('span');
        closeBtnLabel.appendChild(document.createTextNode('Close lightbox'))
        closeBtnLabel.className = 'u-isVisuallyHidden';
        closeBtnLabel.id = 'swipebox-close-label';

        if (closeBtn) {
            closeBtn.parentNode.insertBefore(closeBtnLabel, closeBtn);
            closeBtn.setAttribute('href', '#');
            closeBtn.setAttribute('aria-describedby', 'swipebox-close-label');
            closeBtn.onclick = function(event) {
                event.preventDefault();
            };
            closeBtn.focus();
        }
    };

    /**
     * Detaches events on close
     *
     * @method _onClose
     * @private
     */
    proto._onClose = function() {
        $('#swipebox-overlay').off('click', this._handleOverlayClick);
        $(document.body).off('keydown', this._handleEscKey);
        if (this._previousFocus) {
            this._previousFocus.focus();
        }
    };

    /**
     * Close lightbox on click
     *
     * @method _onOverlayClick
     * @private
     */
    proto._onOverlayClick = function() {
        $.swipebox.close();
    };

    /**
     * Close lightbox on esc
     *
     * @method _onEscKey
     * @private
     */
    proto._onEscKey = function(event) {
        if (event.keyCode === 27) {
            $.swipebox.close();
        }
    };


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////






    module.exports = VideoModalView;

});
