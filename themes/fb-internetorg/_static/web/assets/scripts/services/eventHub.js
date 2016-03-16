/**
 * A global publish/subscribe instance for events
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var PubSub = require('stark/micro/PubSub');

    // eventHub is just a pubsub instance
    return new PubSub();

});
