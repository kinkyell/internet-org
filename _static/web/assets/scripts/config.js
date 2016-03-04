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
        requirejs: '../vendor/requirejs/require',
        jquery: '../vendor/jquery/jquery',
        scroll: '../vendor/scroll/Scroll',
        'intl-tel-input': '../vendor/intl-tel-input/build/js/intlTelInput',
        stark: '../vendor/starkjs/dist/amd',
        promise: '../vendor/es6-promise/promise',
        'gsap-cssPlugin': '../vendor/gsap/src/uncompressed/plugins/CSSPlugin',
        'gsap-scrollToPlugin': '../vendor/gsap/src/uncompressed/plugins/ScrollToPlugin',
        'gsap-tween': '../vendor/gsap/src/uncompressed/TweenLite',
        'gsap-timeline': '../vendor/gsap/src/uncompressed/TimelineMax',
        'gsap-easePack': '../vendor/gsap/src/uncompressed/easing/EasePack',
        'jquery-touchswipe': '../vendor/jquery-touchswipe/jquery.touchSwipe',
        dragdealer: '../vendor/dragdealer/src/dragdealer',
        fastclick: '../vendor/fastclick/lib/fastclick',
        handlebars: '../vendor/handlebars/handlebars',
        'jquery-swipebox': '../vendor/swipebox/src/js/jquery.swipebox',
        platform: '../vendor/platform.js/platform',
        brim: '../vendor/brim/dist/brim',
        scream: '../vendor/scream/dist/scream',
        'es6-promise': '../vendor/es6-promise/promise',
        gsap: '../vendor/gsap/src/uncompressed/TweenMax',
        'nerdery-function-bind': '../vendor/nerdery-function-bind/index',
        'skidding--dragdealer': '../vendor/skidding--dragdealer/src/dragdealer',
        swipebox: '../vendor/swipebox/src/js/jquery.swipebox',
        utils: '../vendor/intl-tel-input/lib/libphonenumber/build/utils'
    },
    map: {
        '*': {
            modernizr: 'modernizr.build',
            templates: 'templates.build',
            TweenLite: 'gsap-tween',
            TimelineLite: 'gsap-timeline'
        }
    },
    shim: {
        'modernizr.build': {
            exports: 'Modernizr'
        },
        'jquery-touchswipe': {
            deps: [
                'jquery'
            ],
            exports: null
        },
        'gsap-tween': {
            exports: 'TweenLite'
        },
        handlebars: {
            exports: 'Handlebars'
        },
        'intl-tel-input': {
            deps: [
                'jquery'
            ],
            exports: null
        },
        'jquery-swipebox': {
            deps: [
                'jquery'
            ],
            exports: null
        },
        brim: {
            exports: 'gajus.Brim'
        },
        scream: {
            exports: 'gajus.Scream'
        }
    },
    waitSeconds: 120,
    packages: [

    ]
});
