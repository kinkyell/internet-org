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
     * Tears down any event binding to handlers.
     *
     * @method _onOpen
     * @private
     */
    proto._onOpen = function() {
        $('#swipebox-overlay').on('click', this._handleOverlayClick);
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method _onClose
     * @private
     */
    proto._onClose = function() {
        $('#swipebox-overlay').off('click', this._handleOverlayClick);
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method _onOverlayClick
     * @private
     */
    proto._onOverlayClick = function() {
        $.swipebox.close();
    };


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////






    module.exports = VideoModalView;

});
