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
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @public
     */
    proto.onEnable = function() {
        this.$element
            .addClass('swipebox-video')
            .attr('rel', 'vimeo' + this.videoNum);

        // NOTE: for some reason it needs to be directly queried. Investigate?
        $('[rel="vimeo'+ this.videoNum + '"]').swipebox({
            vimeoColor: 'ff6b00'
        });
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @public
     */
    proto.onDisable = function() {
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////






    module.exports = VideoModalView;

});
