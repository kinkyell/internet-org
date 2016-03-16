define(["exports", "module"], function (exports, module) {
    "use strict";

    module.exports = function (func, wait, immediate) {
        var timeout;

        return function () {
            var context = this;
            var args = arguments;
            var later = function later() {
                timeout = null;
                if (!immediate) {
                    func.apply(context, args);
                }
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) {
                func.apply(context, args);
            }
        };
    };

    ;
});