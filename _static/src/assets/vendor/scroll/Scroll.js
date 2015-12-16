define(function(require) {
    'use strict';

    var $ = require('jquery');
    require('bez');
    var settings = require('json!data/breakpoints.json');
    var PubSub = require('pubsub');

    var Wheel = require('./Wheel');

    /**
     * @type jQuery
     */
    var $BODY = $(document.body);

    /**
     * @type String
     */
    var EVENT_NAMESPACE = '.scrolltracker';

    /**
     * @type String
     */
    var EVENT_TOUCH_NAMESPACE = '.scrolltrackertouch';

    /**
     * @type String
     * @static
     */
    var EASING = $.bez([0.21, 0.61, 0.35, 1]);

    /**
     * @class Scroll
     * @static
     */
    var Scroll = function() {

        /**
         * @property position
         * @default 0
         * @type Number
         */
        this.position = -1;

        /**
         * @property maxPosition
         * @type Number
         */
        this.maxPosition = Infinity;

        /**
         * @property isEnabled
         * @type Boolean
         * @default false
         */
        this.isEnabled = false;

        /**
         * @property _touchTracker
         * @type Object
         * @private
         */
        this._touchTracker = {
            y: 0,
            startPosition: 0
        };

        /**
         * @property _scrollObj
         * @type Object
         * @private
         */
        this._scrollObj = { y: 0 };

        /**
         * @property _$scrollObj
         * @type jQuery
         * @private
         */
        this._$scrollObj = $(this._scrollObj);

        /**
         * @property _computeScroll
         * @type Boolean
         * @private
         */
        this._computeScroll = false;

        this.setupHandlers();
    };

    var proto = Scroll.prototype = Object.create(PubSub.prototype);
    proto.constructor = Scroll;

    /**
     * @method setupHandlers
     * @chainable
     */
    proto.setupHandlers = function() {
        this.onScrollStep = this.onScrollStep.bind(this);
        this.onMouseWheel = this.onMouseWheel.bind(this);
        this.onMouseWheelComplete = this.onMouseWheelComplete.bind(this);
        this.onTouchStart = this.onTouchStart.bind(this);
        this.onTouchMove = this.onTouchMove.bind(this);
        this.onTouchEnd = this.onTouchEnd.bind(this);

        return this;
    };

    /**
     * @method enable
     * @chainable
     */
    proto.enable = function() {
        if (this.isEnabled) {
            return this;
        }

        this.isEnabled = true;

        Wheel
            .subscribe('wheel', this.onMouseWheel)
            .subscribe('wheel:complete', this.onMouseWheelComplete)
            .enable();

        $BODY.on('touchstart' + EVENT_NAMESPACE, this.onTouchStart);

        return this;
    };

    /**
     * @method disable
     * @chainable
     */
    proto.disable = function() {
        if (!this.isEnabled) {
            return this;
        }

        this.isEnabled = false;

        Wheel
            .disable()
            .unsubscribe('wheel', this.onMouseWheel)
            .unsubscribe('wheel:complete', this.onMouseWheelComplete);

        $BODY.off(EVENT_NAMESPACE);

        return this;
    };

    /**
     * @method setPosition
     * @param {Number} position
     * @chainable
     */
    proto.setPosition = function(position) {
        var oldPosition = this.position;
        this.position = Math.max(0, Math.min(this.maxPosition, Math.round(position)));

        if (oldPosition === this.position) {
            return this;
        }

        return this.publish('scroll', this.position);
    };

    /**
     * @method setMaxPosition
     * @param {Number} max
     * @chainable
     */
    proto.setMaxPosition = function(max) {
        this.maxPosition = max;

        return this;
    };

    proto.computeScroll = function(state) {
        this._computeScroll = state !== false;

        return this;
    };

    /**
     * @method scrollTo
     * @param {Number} position
     * @param {Function} [callback]
     * @chainable
     */
    proto.scrollTo = function(position, callback) {
        var isEnabled = this.isEnabled;

        if (isEnabled) {
            this.disable();
        }

        var self = this;
        Wheel.release();

        this.publish('animate:start', this.position, position);

        if (!this._computeScroll) {
            setTimeout(function() {
                self.setPosition(position);
                self.publish('animate:end', position);

                if (isEnabled) {
                    self.enable();
                }

                if (callback) {
                    callback();
                }
            }, settings.LAYER_DURATION);
        } else {
            this._scrollObj.y = this.position;

            this._$scrollObj
                .stop()
                .animate(
                    { y: position },
                    {
                        duration: settings.LAYER_DURATION,
                        easing: EASING,
                        step: this.onScrollStep,
                        complete: function() {
                            self.publish('animate:end', self.position, position);
                            if (isEnabled) {
                                self.enable();
                            }
                            if (callback) {
                                callback();
                            }
                        }
                    }
                );
        }

        return this;
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * @method onScrollStep
     * @param {Number} position
     * @callback
     */
    proto.onScrollStep = function(position) {
        this.setPosition(position);
    };

    /**
     * @method onMouseWheel
     * @param {String} event
     * @param {Number} delta
     * @callback
     */
    proto.onMouseWheel = function(event, delta) {
        this.setPosition(this.position + delta);
    };

    /**
     * @method onMouseWheelComplete
     * @param {String} event
     * @callback
     */
    proto.onMouseWheelComplete = function(event) {
        this.publish('scroll:complete', this.position);
    };

    /**
     * @method onTouchStart
     * @param {jQuery.Event} e
     * @callback
     */
    proto.onTouchStart = function(e) {
        this._touchTracker.y = e.originalEvent.touches[0].pageY;
        this._touchTracker.startPosition = this.position;

        $BODY
            .on('touchmove' + EVENT_NAMESPACE + EVENT_TOUCH_NAMESPACE, this.onTouchMove)
            .on('touchend' + EVENT_NAMESPACE + EVENT_TOUCH_NAMESPACE, this.onTouchEnd)
            .on('touchcancel' + EVENT_NAMESPACE + EVENT_TOUCH_NAMESPACE, this.onTouchEnd);
    };

    /**
     * @method onTouchMove
     * @param {jQuery.Event} e
     * @callback
     */
    proto.onTouchMove = function(e) {
        e.preventDefault();
        var y = e.originalEvent.touches[0].pageY;
        var delta = -(y -this._touchTracker.y);

        this.setPosition(this._touchTracker.startPosition + delta);
    };

    /**
     * @method onTouchEnd
     * @param {jQuery.Event} e
     * @callback
     */
    proto.onTouchEnd = function(e) {
        $BODY.off(EVENT_TOUCH_NAMESPACE);

        this.publish('scroll:complete', this.position);
    };

    return Scroll;

});