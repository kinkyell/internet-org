/**
 * A global manager for the view window
 *
 * @fileoverview
 */
define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var tweenAsync = require('util/tweenAsync');
    var $ = require('jquery');
    var breakpointManager = require('services/breakpointManager');
    var apiService = require('services/apiService');
    var AnimationQueue = require('util/AnimationQueue');

    var parseUrl = require('stark/string/parseUrl');
    var log = require('util/log');
    var vwConfig = require('appConfig').viewWindow;
    var eventHub = require('services/eventHub');
    var assetLoader = require('services/assetLoader');

    // speed of shift and feature transitions
    var TRANSITION_SPEED = require('appConfig').animationSpeeds.PANEL_SHIFT;

    var BG_IMG_REGEX = /^url\((.+)\)$/;
    var SCROLL_BARIER = 70;

    /**
     * Constructor for ViewWindow
     *
     * @class ViewWindow
     * @constructor
     */
    var ViewWindow = function() {
        this._init();
    };

    /**
     * Set up instance
     *
     * @method _init
     * @private
     */
    ViewWindow.prototype._init = function() {
        // DOM refs

        this.$element = $('.js-viewWindow');
        this.element = this.$element[0];

        this.$panels = this.$element.children();
        this.$feature = this.$element.find('.viewWindow-panel_feature');
        this.$story = this.$element.find('.viewWindow-panel_story');
        if (!this.element) {
            throw new TypeError('ViewWindow: no view windows found.');
        }

        // init properties
        this._featureQueue = new AnimationQueue();
        this._storyQueue = new AnimationQueue();
        this._shiftQueue = new AnimationQueue();
        this._isShifted = false;
        this._featureImage = null;
        this._handlePanelScroll = this._onPanelScroll.bind(this);
        this.$story.children().on('scroll', this._handlePanelScroll);

        // get bg image if available
        var childImg = this.$feature.find('.viewWindow-panel-content-inner').css('background-image');
        if (BG_IMG_REGEX.test(childImg)) {
            this._featureImage = childImg.match(BG_IMG_REGEX)[1];
            this._featureImage = parseUrl(this._featureImage).href;
        }
    };

    /**
     * Replace feature area with an image
     *
     * @method replaceFeatureImage
     * @param {String} imagePath path for image to set as background image
     * @param {String} direction Direction to animate to/from
     * @return {Promise} resolves when finished
     */
    ViewWindow.prototype.replaceFeatureImage = function(imagePath, direction) {
        imagePath = parseUrl(imagePath).href;
        var swapImage = function() {
            return this._featureQueue.queue(function() {

                var $panel;

                if (this._featureImage === imagePath || !imagePath) {
                    if (!imagePath) {
                        log('ViewWindow: [warning] image path is undefined!');
                    }
                    return Promise.resolve(this.$feature.children());
                }

                $panel = this._getPanelWrap();
                $panel.children().css({
                    'background-color': '#efede4',
                    'background-image': 'url(' + imagePath + ')'
                });
                this._featureImage = imagePath;

                return this._updatePanel(
                    $panel,
                    this.$feature,
                    direction
                );
            }, this);
        }.bind(this);

        return assetLoader.loadImage(imagePath).then(swapImage, swapImage);
    };

    /**
     * Replace feature area with an html
     *
     * @method replaceFeatureContent
     * @param {String} html HTML content to add
     * @param {String} direction Direction to animate to/from
     * @param {String} bgImg Optional background images to swap on container
     * @return {Promise} resolves when finished
     */
    ViewWindow.prototype.replaceFeatureContent = function(html, direction, bgImg) {
            
        return this._featureQueue.queue(function() {
            var $panel = this._getPanelWrap();

            $panel.children().append(html);
            this._featureImage = html;

            if (typeof bgImg !== 'undefined') {
                $panel.children().css({
                    'background-color': '#efede4',
                    'background-image': 'url(' + bgImg + ')'
                });
            }

            return this._updatePanel(
                $panel,
                this.$feature,
                direction,
                true
            );
        }, this);
    };


    ViewWindow.prototype.replaceFeatureContentBlog = function(html, direction, bgImg) {
        return this._featureQueue.queue(function() {
            var $panel = this._getPanelWrapBlog();

            $panel.children().append(html);
            this._featureImage = html;
            if (typeof bgImg !== 'undefined') {
                $panel.children().css({
                    'background-color': '#efede4',
                    'background-image': 'url(' + bgImg + ')'
                });
            }

            return this._updatePanel(
                $panel,
                this.$feature,
                direction,
                true
            );
        }, this);
    };

    /**
     * Replace story area with an html
     *
     * @method replaceStoryContent
     * @param {String} html HTML content to add
     * @param {String} direction Direction to animate to/from
     * @return {Promise} resolves when finished
     */
    ViewWindow.prototype.replaceStoryContent = function(html, direction) {

        
        return this._storyQueue.queue(function() {

            var $panel = this._getPanelWrap();
            $panel.children().append(html);

            return this._updatePanel(
                $panel,
                this.$story,
                direction,
                false,
                true
            );
        }, this);
    };

    ViewWindow.prototype.replaceStoryContentBlog = function(html, direction) {

        return this._storyQueue.queue(function() {

            var $panel = this._getPanelWrapBlogContent();

            $panel.children().append(html);
            
            return this._updatePanel(
                $panel,
                this.$story,
                direction,
                false,
                true
            );
        }, this);
    };

    /**
     * Get bare element for new panel content
     *
     * @method _getPanelWrap
     * @return {jQuery} content wrapper element
     * @private
     */
    ViewWindow.prototype._getPanelWrap = function() {
        var contentDiv = document.createElement('div');
        contentDiv.className = 'viewWindow-panel-content';
        var contentInnerDiv = document.createElement('div');
        contentInnerDiv.className = 'viewWindow-panel-content-inner';


        contentDiv.appendChild(contentInnerDiv);
        return $(contentDiv);
    };

    ViewWindow.prototype._getPanelWrapBlog = function() {
        var contentDiv = document.createElement('div');
        contentDiv.className = 'viewWindow-panel-content-blog';
        var contentInnerDiv = document.createElement('div');
        contentInnerDiv.className = 'viewWindow-panel-content-blog-inner';
        contentDiv.appendChild(contentInnerDiv);
        return $(contentDiv);
    };

    ViewWindow.prototype._getPanelWrapBlogContent = function() {
        var contentDiv = document.createElement('div');
        contentDiv.className = 'viewWindow-panel-content-blogc';
        var contentInnerDiv = document.createElement('div');
        contentInnerDiv.className = 'viewWindow-panel-content-blogc-inner';
        contentDiv.appendChild(contentInnerDiv);
        return $(contentDiv);
    };

    /**
     * Get properties for animation
     *
     * @method _getAnimProps
     * @return {jQuery} content wrapper element
     * @private
     */
    ViewWindow.prototype._getAnimProps = function(direction) {
        var inOpts = {
            ease: vwConfig.FEATURE_EASE()[vwConfig.EASE_DIRECTION]
        };
        var outOpts = {
            ease: vwConfig.FEATURE_EASE()[vwConfig.EASE_DIRECTION]
        };
        var directionInvert = document.documentElement.dir === 'ltr' ? 1 : -1;
        console.log("direction : ", direction);
        switch (direction.toLowerCase()) {
        case 'top':
            inOpts.yPercent = -100;
            outOpts.yPercent = 100;
            break;
        case 'bottom':
            inOpts.yPercent = 100;
            outOpts.yPercent = -100;
            if (breakpointManager.isMobile) {
                outOpts.yPercent = -50;
                outOpts.opacity = 0.5;
                outOpts.transform = 'scale(0.85)';
            }
            break;
        case 'left':
            outOpts.xPercent = directionInvert * 100;
            if (!breakpointManager.isMobile) {
                inOpts.xPercent = directionInvert * -100;
            }
            break;
        case 'right':
            inOpts.xPercent = directionInvert * 100;
            if (!breakpointManager.isMobile) {
                outOpts.xPercent = directionInvert * -100;
            }
            break;
        case 'none':
            break;
        default:
        }

        return {
            in: inOpts,
            out: outOpts
        };
    };

    /**
     * Slide panel based on configuration
     *
     * @method _updatePanel
     * @param {jQuery} $panel Content to add
     * @param {jQuery} $target Target panel to update
     * @param {String} direction Direction to animate from
     * @param {Boolean} doublePanel Flag for setting interaction on both panels
     * @return {Promise} resolves when complete with animation
     * @private
     */
    ViewWindow.prototype._updatePanel = function($panel, $target, direction, doublePanel, scroller) {
        var opts = this._getAnimProps(direction);
        var $newPanel;
        var $removedPanel;
        // var addMethod = direction === 'left' ? 'prepend' : 'append';
        //console.log();
        
       
        //console.log(mainHTML);
        if (direction === 'left') {
            $target.prepend($panel);
            $removedPanel = $panel.next();
        } else {
            $target.append($panel);
            $removedPanel = $panel.prev();
        }
        $newPanel = $panel;
        doublePanel = doublePanel || false;

        $target.addClass('isAnimating');

        if (scroller) {
            $removedPanel.off('scroll', this._handlePanelScroll);
            $newPanel.on('scroll', this._handlePanelScroll);
            this._currentScroll = 0;
            eventHub.publish('viewWindow:scrollBarrier', this._currentScroll > SCROLL_BARIER);
        }

        var cleanup = function() {
            
            
            $removedPanel.remove();
            $target
                .removeClass('isAnimating')
                .toggleClass('isDouble', doublePanel);
        };

        if (direction.toLowerCase() === 'none') {
            cleanup();
            return Promise.resolve($newPanel.children());
        }
     
            return Promise.all([
                tweenAsync.from($newPanel[0], TRANSITION_SPEED, opts.in),
                tweenAsync.to($removedPanel[0], TRANSITION_SPEED, opts.out)
            ]).then(cleanup).then(function() {
                
                     
                return $newPanel.children();
            });
       
        
    };

    /**
     * Toggle shift of 3up container
     *
     * @method shift
     * @param {Boolean} silent Flag to skip animation
     * @return {Promise} rejects if already animating, resolves when complete
     */
    ViewWindow.prototype.shift = function(silent) {
        return this._shiftQueue.queue(function() {
            var percent = breakpointManager.isMobile ? 50 : 33.333;
            var shiftOn = !this._isShifted;
            var sign;
            var method;
            var directionInvert = document.documentElement.dir === 'ltr' ? 1 : -1;
            var animateElement = breakpointManager.isMobile ? this.element.lastElementChild : this.element;

            this.$panels.addClass('isAnimating');

            if (shiftOn) {
                sign = 1;
                method = 'last';
            } else {
                sign = -1;
                method = 'first';
            }

            this.$element.toggleClass('isShifted', shiftOn);

            this._isShifted = !this._isShifted;

            if (silent) {
                this.$panels.removeClass('isAnimating');
                return Promise.resolve();
            }

            return tweenAsync.from(animateElement, TRANSITION_SPEED, {
                xPercent: directionInvert * sign * percent,
                ease: vwConfig.SHIFT_EASE()[vwConfig.EASE_DIRECTION],
                onComplete: function() {
                    this.$panels.removeClass('isAnimating').removeClass('isActive');
                    this.$panels[method]().addClass('isActive');
                },
                callbackScope: this
            });
        }, this);
    };

    ViewWindow.prototype.changetoFullScreen = function(displayoption) {
        //$('.header').css({"height": "100px"
         $('.viewWindow').css({"left": "100%"});
        setTimeout(function(){
            $('.viewWindow').css({"width": "100%", "display":"block", "margin" : "auto"});
            $('.viewWindow-panel').css({"width": "100%", "height": "auto", "left":"0"});
            /*
            var headerHTML = $('.header').html();
            var headerDiv = document.createElement('div');
            headerDiv.className = 'innerHeader';
            $(headerDiv).html(headerHTML);
            $('.header').html(headerDiv);
            */
            $('.viewWindow').animate({left:"0"}, 500, function() {
                // Animation complete.
               // $('.innerHeader').css({"width": "100%", "margin": "auto", "position": "relative"});
             
            });

            $('.header-logo').on("click",function(event) {
              alert("Clicked");
                //this.changetoOriginal();
                $('.header').css({"width": "", "margin": "", "height": "", "background-color": ""});
                $('.viewWindow').css({"max-width": "", "width": "", "left": "", "margin" : ""});
                $('.viewWindow-panel').css({"width": "", "height": "", "left":""});
                $('.viewWindow').unbind( 'DOMMouseScroll mousewheel');

                $('.header-logo').unbind("click");
            });

            var ImageDisplay = displayoption;
            $('.viewWindow').on( 'DOMMouseScroll mousewheel', function ( event ) {
              if( event.originalEvent.detail > 0 || event.originalEvent.wheelDelta < 0 ) { //alternative options for wheelData: wheelDeltaX & wheelDeltaY
                //scroll down
                var height = $('.innerHeader').css("height");
                if(height!="100px") {
                    $('.header').css({"height": "100px", "background-color": "#EFEDE4"});
                    //$('.innerHeader').css({"height": "100px", "background-color": "#EFEDE4"});
                    //console.log('Down again');    
                }
              } else {
                if(($('.page-title-panel').offset().top == 32) || ($('.page-title-panel').offset().top == 0)) {
                        $('.header').css({"height": "", "background-color": ""});    
                    } 
               
              }
              //prevent page fom scrolling
              return true;
            });





        }, 300);

       
    }

    ViewWindow.prototype.changetoOriginal = function() {
       /*
        var headerHTML = $('.innerHeader').html();
        $('.innerHeader').remove();
        $('.header').html(headerHTML);
*/
       // $('.innerHeader').css({"width": "", "margin": "", "position": ""});
        $('.header').css({"width": "", "margin": "", "height": "", "background-color": ""});
        $('.viewWindow').css({"max-width": "", "width": "", "left": "", "margin" : ""});
        $('.viewWindow-panel').css({"width": "", "height": "", "left":""});
        $('.viewWindow').unbind( 'DOMMouseScroll mousewheel');

        $('.header-logo').unbind("click");

    }

    /**
     * Get state of shifting
     *
     * @method isShifted
     * @return {Boolean} whether container is shifted over
     */
    ViewWindow.prototype.isShifted = function() {
        return this._isShifted;
    };

    /**
     * Get current content panel
     *
     * @method getCurrentStory
     * @return {jQuery} content panel
     */
    ViewWindow.prototype.getCurrentStory = function() {
       console.log(this.$story);
        return Promise.resolve(this.$story.children().children());
    };

    /**
     * Get current feature panel
     *
     * @method getCurrentFeature
     * @return {jQuery} content panel
     */
    ViewWindow.prototype.getCurrentFeature = function() {
        return Promise.resolve(this.$feature.children().children());
    };

    /**
     * Load in homepage content
     *
     * @method loadHomepageContent
     */
    ViewWindow.prototype.loadHomepageContent = function() {
        
        if (this._homepageResolution) {
            return this._homepageResolution;
        }
        var $home = this.$element.find('.viewWindow-panel-content-inner_home');
        if ($home.children().length > 0) {
            this._homepageResolution = Promise.resolve();
            return this._homepageResolution;
        }

        this._homepageResolution = apiService.getHomepageContent().then(function(content) {
            var homeEl = $home[0];
            homeEl.parentNode.replaceChild(content.el, homeEl);
        }.bind(this));

        return this._homepageResolution;
    };

    /**
     * Load in homepage content
     *
     * @method _onPanelScroll
     */
    ViewWindow.prototype._onPanelScroll = function(event) {
        var oldScroll = this._currentScroll;
        this._currentScroll = event.target.scrollTop;
        if (oldScroll <= SCROLL_BARIER && this._currentScroll > SCROLL_BARIER) {
            eventHub.publish('viewWindow:scrollBarrier', this._currentScroll > SCROLL_BARIER);
        } else if (oldScroll > SCROLL_BARIER && this._currentScroll <= SCROLL_BARIER) {
            eventHub.publish('viewWindow:scrollBarrier', this._currentScroll > SCROLL_BARIER);
        }
    };

    return new ViewWindow();

});
