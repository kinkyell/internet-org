/**
 * Application configuration declaration.
 *
 * This configuration file is shared between the website and the build script so
 * that values don't have to be duplicated across environments. Any non-shared,
 * environment-specific configuration should placed in appropriate configuration
 * files.
 *
 * Paths to vendor libraries may be added here to provide short aliases to
 * otherwise long and arbitrary paths. If you're using bower to manage vendor
 * scripts, running `grunt inject` will automatically add paths aliases as
 * needed.
 *
 * @example
 *     paths: {
 *         'jquery': '../vendor/jquery/jquery',
 *         'jquery-cookie': '../vendor/jquery-cookie/jquery-cookie'
 *     }
 *
 * Shims provide a means of managing dependencies for non-modular, or non-AMD
 * scripts. For example, jQuery UI depends on jQuery, but it assumes jQuery is
 * available globally. Because RequireJS loads scripts asynchronously, jQuery
 * may or may not be available which will cause a runtime error. Shims solve
 * this problem.
 *
 * @example
 *     shim: {
 *         'jquery-cookie': {
 *             deps: ['jquery'],
 *             exports: null
 *          }
 *     }
 */
require.config({
    paths: {
        // this empty string tells r.js to use single quotes when injecting
        // bower modules automatically. Otherwise it defaults to double quotes.
        requirejs: '../vendor/requirejs/require',
        jquery: '../vendor/jquery/jquery',
        scroll: '../vendor/scroll/Scroll',
        stark: '../vendor/starkjs/dist/amd',
        promise: '../vendor/es6-promise/promise',
        'gsap-cssPlugin': '../vendor/gsap/src/uncompressed/plugins/CSSPlugin',
        'gsap-tween': '../vendor/gsap/src/uncompressed/TweenLite',
        'gsap-timeline': '../vendor/gsap/src/uncompressed/TimelineLite'
    },

    map: {
        '*': {
            'modernizr': 'modernizr.build',
            'TweenLite': 'gsap-tween',
            'TimelineLite': 'gsap-timeline'
        }
    },

    shim: {
        'modernizr.build': {
            exports: 'Modernizr'
        },
        'gsap-tween': {
            exports: 'TweenLite'
        }
    },

    waitSeconds: 120
});
