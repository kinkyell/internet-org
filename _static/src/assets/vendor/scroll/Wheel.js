define(function(require) {
    'use strict';

    var $ = require('jquery');
    var PubSub = require('pubsub');

    /**
     * @type jQuery
     */
    var $WINDOW = $(window);

    /**
     * @type Boolean
     */
    var RELEASED = false;

    /**
     * @type Number
     */
    var DIRECTION = 0;

    /**
     * @type Number[]
     */
    var DELTA = [];

    /**
     * @type Number
     */
    var DELTA_LIMIT = 3;

    /**
     * @type Number
     */
    var DELAY = 250;

    /**
     * @type Number
     */
    var _timeout;

    /**
     * @type Number
     */
    var _timeout2;

    /**
     * @type Function
     * @param {Number} delta
     * @returns {Number}
     * @private
     */
    var _direction = function(delta) {
        return delta > 0 ? 1 : -1;
    };

    /**
     * @type Function
     * @param {Number} delta
     * @private
     */
    var _logReleasedDelta = function(delta) {
        DELTA.unshift(Math.abs(delta));
        DELTA.length = DELTA_LIMIT;
    };

    /**
     * @type Function
     * @param {Number}delta
     * @returns {boolean}
     * @private
     */
    var _checkReleasedDelta = function(delta) {
        if (DELTA.length < DELTA_LIMIT) {
            return false;
        }

        var i = DELTA.length - 1;
        delta = Math.abs(delta);

        for (; i > -1; i--) {
            if (delta <= DELTA[i]) {
                return false;
            }
        }

        return true;
    };

    /**
     * @class Wheel
     * @static
     */
    var Wheel = {

        /**
         * @property isEnabled
         * @type Boolean
         * @default false
         */
        isEnabled: false,

        /**
         * @method enable
         * @chainable
         */
        enable: function() {
            if (this.isEnabled) {
                return this;
            }

            this.isEnabled = true;

            $WINDOW.on('wheel', this.onMouseWheel);

            return this;
        },

        /**
         * @method disable
         * @chainable
         */
        disable: function() {
            if (!this.isEnabled) {
                return this;
            }

            this.isEnabled = false;

            $WINDOW.off('wheel', this.onMouseWheel);

            return this;
        },

        /**
         * @method release
         * @chainable
         */
        release: function() {
            RELEASED = true;
            DELTA.length = 0;

            return this;
        },

        /**
         * @method _handleWheel
         * @param {Number} delta
         * @private
         */
        _handleWheel: function(delta) {
            clearTimeout(_timeout);

            DIRECTION = _direction(delta);
            Wheel.publish('wheel', delta);

            _timeout = setTimeout(this.onMouseWheelComplete, DELAY);
        },

        /**
         * @method _handleReleasedWheel
         * @param {Number} delta
         * @private
         */
        _handleReleasedWheel: function(delta) {
            clearTimeout(_timeout2);

            if (_direction(delta) !== DIRECTION || _checkReleasedDelta(delta)) {
                this.onMouseWheelReleaseComplete();
                return;
            }

            _logReleasedDelta(delta);

            _timeout2 = setTimeout(this.onMouseWheelReleaseComplete, DELAY);

        },

        //////////////////////////////////////////////////////////////////////////////////
        // EVENT HANDLERS
        //////////////////////////////////////////////////////////////////////////////////

        /**
         * @method onMouseWheel
         * @param {jQuery.Event} e
         * @callback
         */
        onMouseWheel: function(e) {
            var delta = e.originalEvent.deltaY;

            if (RELEASED) {
                Wheel._handleReleasedWheel(delta);
            } else {
                Wheel._handleWheel(delta);
            }
        },

        /**
         * @method onMouseWheelComplete
         * @callback
         */
        onMouseWheelComplete: function() {
            Wheel.publish('wheel:complete');
        },

        /**
         * @method onMouseWheelReleaseComplete
         * @callback
         */
        onMouseWheelReleaseComplete: function() {
            RELEASED = false;
        }

    };

    PubSub.apply(Wheel);

    return Wheel;

});