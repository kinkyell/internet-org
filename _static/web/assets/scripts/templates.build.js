define(['handlebars'], function(Handlebars) {

this["JST"] = this["JST"] || {};

this["JST"]["archive-result"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
    var helper;

  return "    <div class=\"media media_inline\">\n        <div class=\"media-figure\">\n            <div class=\"imgWrap isLoaded\"><img src=\""
    + this.escapeExpression(((helper = (helper = helpers.image || (depth0 != null ? depth0.image : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"image","hash":{},"data":data}) : helper)))
    + "\" alt=\"story image\"></div>\n        </div>\n        <div class=\"media-bd\">\n";
},"3":function(depth0,helpers,partials,data) {
    return "        </div>\n    </div>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div class=\"resultsList-list-item\">\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.image : depth0),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "    <div class=\"feature feature_tight\">\n        <div class=\"feature-hd\">\n            <h3 class=\"hdg hdg_4\">"
    + ((stack1 = ((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "</h3>\n        </div>\n        <div class=\"feature-date\">\n            <div class=\"hdg hdg_6 mix-hdg_italic mix-hdg_gray\">"
    + ((stack1 = ((helper = (helper = helpers.date || (depth0 != null ? depth0.date : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"date","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "</div>\n        </div>\n        <div class=\"feature-bd\">\n            <p class=\"bdcpy\">"
    + ((stack1 = ((helper = (helper = helpers.desc || (depth0 != null ? depth0.desc : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"desc","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "</p>\n        </div>\n        <div class=\"feature-cta\">\n            <a href=\""
    + alias3(((helper = (helper = helpers.url || (depth0 != null ? depth0.url : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"url","hash":{},"data":data}) : helper)))
    + "\" class=\"link link_sm js-stateLink\" data-type=\"titled\" data-title=\""
    + alias3(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper)))
    + "\" data-date=\""
    + alias3(((helper = (helper = helpers.date || (depth0 != null ? depth0.date : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"date","hash":{},"data":data}) : helper)))
    + "\" data-social=\"true\">"
    + alias3(((helper = (helper = helpers.readMoreText || (depth0 != null ? depth0.readMoreText : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"readMoreText","hash":{},"data":data}) : helper)))
    + "</a>\n        </div>\n    </div>\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.image : depth0),{"name":"if","hash":{},"fn":this.program(3, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "</div>\n";
},"useData":true});

this["JST"]["article-header"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
    return "introBlock_media";
},"3":function(depth0,helpers,partials,data) {
    var helper;

  return "topicBlock-hd_theme"
    + this.escapeExpression(((helper = (helper = helpers.theme || (depth0 != null ? depth0.theme : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"theme","hash":{},"data":data}) : helper)));
},"5":function(depth0,helpers,partials,data) {
    var helper;

  return "            <div class=\"topicBlock-bd\">\n                <p class=\"bdcpy\">"
    + this.escapeExpression(((helper = (helper = helpers.description || (depth0 != null ? depth0.description : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"description","hash":{},"data":data}) : helper)))
    + "</p>\n            </div>\n";
},"7":function(depth0,helpers,partials,data) {
    var helper;

  return "            <div class=\"topicBlock-media isHidden u-isHiddenMedium\" aria-hidden=\"true\">\n                <img src=\""
    + this.escapeExpression(((helper = (helper = helpers.image || (depth0 != null ? depth0.image : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"image","hash":{},"data":data}) : helper)))
    + "\" alt=\"\">\n            </div>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper;

  return "<!-- END MOBILE ONLY CONTENT HERE -->\n<div class=\"introBlock "
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.image : depth0),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "\">\n    <div class=\"introBlock-inner\">\n        <div class=\"topicBlock\">\n            <div class=\"topicBlock-hd topicBlock-hd_mega "
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.theme : depth0),{"name":"if","hash":{},"fn":this.program(3, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "\">\n                <h2 class=\"hdg hdg_2 mix-hdg_bold\">"
    + this.escapeExpression(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper)))
    + "</h2>\n            </div>\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.desc : depth0),{"name":"if","hash":{},"fn":this.program(5, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.image : depth0),{"name":"if","hash":{},"fn":this.program(7, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "        </div>\n    </div>\n</div>\n";
},"useData":true});

this["JST"]["home-feature"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    return "<div class=\"featureContent\">\n    <a href=\"/approach/tertiary\" class=\"tertiaryCta js-stateLink\" data-type=\"titled\" data-theme=\"mission\" data-title=\"Tertiary Page Title\">\n        Internet.org App\n        <span class=\"circleBtn circleBtn_themeMission\"></span>\n    </a>\n</div>";
},"useData":true});

this["JST"]["page-title-panel"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
    var helper;

  return "                <div class=\"topicBlock-subHd\">\n                    <div class=\"hdg hdg_6 mix-hdg_italic mix-hdg_gray\">"
    + this.escapeExpression(((helper = (helper = helpers.date || (depth0 != null ? depth0.date : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"date","hash":{},"data":data}) : helper)))
    + "</div>\n                </div>\n";
},"3":function(depth0,helpers,partials,data) {
    var helper;

  return "                <div class=\"topicBlock-bd\">\n                    <p class=\"bdcpy\">"
    + this.escapeExpression(((helper = (helper = helpers.desc || (depth0 != null ? depth0.desc : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"desc","hash":{},"data":data}) : helper)))
    + "</p>\n                </div>\n";
},"5":function(depth0,helpers,partials,data) {
    return "    <div class=\"introBlock-ft introBlock-ft_rule introBlock-ft_social\">\n    	<ul class=\"socialParade\">\n    		<li><a class=\"socialParade-icon socialParade-icon_fb\" href=\"https://fb.me/Internetdotorg\" target=\"_blank\">Facebook</a></li>\n    		<li><a class=\"socialParade-icon socialParade-icon_tw\" href=\"https://twitter.com/internet_org\" target=\"_blank\">Twitter</a></li>\n    	</ul>\n    </div>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper;

  return "<div class=\"introBlock introBlock_fill\">\n    <div class=\"introBlock-inner\">\n        <div class=\"container\">\n            <div class=\"topicBlock\">\n                <div class=\"topicBlock-hd topicBlock-hd_plus\">\n                    <h2 class=\"hdg hdg_2\">"
    + this.escapeExpression(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper)))
    + "</h2>\n                </div>\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.date : depth0),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.desc : depth0),{"name":"if","hash":{},"fn":this.program(3, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "            </div>\n        </div>\n    </div>\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.social : depth0),{"name":"if","hash":{},"fn":this.program(5, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "</div>\n";
},"useData":true});

this["JST"]["search-input-panel"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var helper;

  return "<div class=\"introBlock introBlock_fill\">\n    <div class=\"introBlock-inner\">\n        <div class=\"container\">\n            <form class=\"searchBox searchBox_inPanel js-searchFormView\" role=\"search\">\n                <label for=\"mainMenu-search\" class=\"searchBox-icon searchBox-icon_lrg js-searchView-trigger\">\n                    <svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" preserveAspectRatio=\"xMidYMid\" width=\"40\" height=\"40\" viewBox=\"0 0 40 40\">\n                      <path d=\"M39.724,35.318 L29.676,25.269 C31.311,22.737 32.449,19.451 32.449,16.219 C32.449,7.268 25.165,-0.011 16.217,-0.011 C7.266,-0.011 -0.012,7.268 -0.012,16.219 C-0.012,25.169 7.266,32.450 16.217,32.450 C19.449,32.450 22.700,31.315 25.234,29.679 L35.281,39.727 C35.615,40.062 36.166,40.062 36.498,39.727 L39.724,36.535 C40.061,36.201 40.061,35.654 39.724,35.318 ZM3.573,16.010 C3.573,9.155 9.152,3.574 16.009,3.574 C22.868,3.574 28.444,9.155 28.444,16.010 C28.444,22.869 22.868,28.447 16.009,28.447 C9.152,28.447 3.573,22.869 3.573,16.010 Z\"></path>\n                    </svg>\n                    <span class=\"u-isVisuallyHidden\">Search</span>\n                </label>\n                <input type=\"search\" id=\"mainMenu-search\" class=\"searchBox-input searchBox-input_tall js-searchView-input\" value=\""
    + this.escapeExpression(((helper = (helper = helpers.searchText || (depth0 != null ? depth0.searchText : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"searchText","hash":{},"data":data}) : helper)))
    + "\" name=\"s\" placeholder=\"\">\n            </form>\n        </div>\n    </div>\n</div>\n";
},"useData":true});

this["JST"]["search-result"] = Handlebars.template({"1":function(depth0,helpers,partials,data) {
    return "                   data-type=\"panel\"\n                   data-theme=\"approach\"\n";
},"3":function(depth0,helpers,partials,data) {
    return "                    data-type=\"titled\"\n";
},"5":function(depth0,helpers,partials,data) {
    return "                    data-social=\"true\"\n                ";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div class=\"resultsList-list-item\">\n    <div class=\"feature feature_tight\">\n        <div class=\"feature-hd\">\n            <a href=\""
    + alias3(((helper = (helper = helpers.url || (depth0 != null ? depth0.url : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"url","hash":{},"data":data}) : helper)))
    + "\"\n               class=\"mix-link_small js-stateLink\"\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.isStory : depth0),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.program(3, data, 0),"data":data})) != null ? stack1 : "")
    + "               data-title=\""
    + alias3(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper)))
    + "\"\n               data-date=\""
    + alias3(((helper = (helper = helpers.date || (depth0 != null ? depth0.date : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"date","hash":{},"data":data}) : helper)))
    + "\"\n               data-image=\""
    + alias3(((helper = (helper = helpers.image || (depth0 != null ? depth0.image : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"image","hash":{},"data":data}) : helper)))
    + "\"\n               data-mobile-image=\""
    + alias3(((helper = (helper = helpers.mobileImage || (depth0 != null ? depth0.mobileImage : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"mobileImage","hash":{},"data":data}) : helper)))
    + "\"\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.isPost : depth0),{"name":"if","hash":{},"fn":this.program(5, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "><h2 class=\"hdg hdg_4\">"
    + ((stack1 = ((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "</h2></a>\n        </div>\n        <div class=\"feature-bd\">\n            <p class=\"bdcpy\">"
    + ((stack1 = ((helper = (helper = helpers.desc || (depth0 != null ? depth0.desc : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"desc","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "</p>\n        </div>\n        <div class=\"feature-cta\">\n            <a href=\""
    + alias3(((helper = (helper = helpers.url || (depth0 != null ? depth0.url : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"url","hash":{},"data":data}) : helper)))
    + "\"\n               class=\"link mix-link_small js-stateLink\"\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.isStory : depth0),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.program(3, data, 0),"data":data})) != null ? stack1 : "")
    + "               data-title=\""
    + alias3(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"title","hash":{},"data":data}) : helper)))
    + "\"\n               data-date=\""
    + alias3(((helper = (helper = helpers.date || (depth0 != null ? depth0.date : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"date","hash":{},"data":data}) : helper)))
    + "\"\n               data-image=\""
    + alias3(((helper = (helper = helpers.image || (depth0 != null ? depth0.image : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"image","hash":{},"data":data}) : helper)))
    + "\"\n               data-mobile-image=\""
    + alias3(((helper = (helper = helpers.mobileImage || (depth0 != null ? depth0.mobileImage : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"mobileImage","hash":{},"data":data}) : helper)))
    + "\"\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.isPost : depth0),{"name":"if","hash":{},"fn":this.program(5, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + ">"
    + alias3(((helper = (helper = helpers.readMoreText || (depth0 != null ? depth0.readMoreText : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"readMoreText","hash":{},"data":data}) : helper)))
    + "</a>\n        </div>\n    </div>\n</div>\n";
},"useData":true});

this["JST"]["search-results-header"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div class=\"u-isHiddenMedium\">\n    <form class=\"searchBox searchBox_inPanel searchBox_inPanelMobile js-searchFormView\" role=\"search\">\n        <label for=\"mainMenu-search\" class=\"searchBox-icon searchBox-icon_inPanelMobile js-searchView-trigger\">\n            <svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" preserveAspectRatio=\"xMidYMid\" width=\"40\" height=\"40\" viewBox=\"0 0 40 40\">\n                <path d=\"M39.724,35.318 L29.676,25.269 C31.311,22.737 32.449,19.451 32.449,16.219 C32.449,7.268 25.165,-0.011 16.217,-0.011 C7.266,-0.011 -0.012,7.268 -0.012,16.219 C-0.012,25.169 7.266,32.450 16.217,32.450 C19.449,32.450 22.700,31.315 25.234,29.679 L35.281,39.727 C35.615,40.062 36.166,40.062 36.498,39.727 L39.724,36.535 C40.061,36.201 40.061,35.654 39.724,35.318 ZM3.573,16.010 C3.573,9.155 9.152,3.574 16.009,3.574 C22.868,3.574 28.444,9.155 28.444,16.010 C28.444,22.869 22.868,28.447 16.009,28.447 C9.152,28.447 3.573,22.869 3.573,16.010 Z\"></path>\n            </svg>\n            <span class=\"u-isVisuallyHidden\">Search</span>\n        </label>\n        <input type=\"search\" id=\"mainMenu-search\" class=\"searchBox-input searchBox-input_inPanelMobile js-searchView-input\" name=\"s\" placeholder=\"\" value=\""
    + alias3(((helper = (helper = helpers.searchText || (depth0 != null ? depth0.searchText : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"searchText","hash":{},"data":data}) : helper)))
    + "\">\n    </form>\n</div>\n<div class=\"contentCol\">\n    <div class=\"container\">\n        <div class=\"resultsList\">\n            <div class=\"resultsList-hd\">\n                <div class=\"hdg hdg_6 mix-hdg_italic mix-hdg_gray\"><span class=\"js-searchState-num\"></span> Results Found</div>\n            </div>\n            <div class=\"resultsList-list js-searchState-results\" id=\"search-results\">\n            </div>\n            <div class=\"resultsList-ft js-searchState-ft\" style=\"display: none;\">\n                <div class=\"resultsList-list resultsList-list_spread\">\n                    <div class=\"resultsList-list-item\">\n                        <button type=\"button\" class=\"btn js-ShowMoreView\" data-src=\"search\" data-target=\"search-results\" data-args=\""
    + alias3(((helper = (helper = helpers.searchText || (depth0 != null ? depth0.searchText : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"searchText","hash":{},"data":data}) : helper)))
    + "\">Show More</button>\n                    </div>\n                </div>\n            </div>\n        </div>\n    </div>\n    <div class=\"contentCol contentCol_tight\">\n        <div class=\"container\">\n            "
    + ((stack1 = ((helper = (helper = helpers.copyright || (depth0 != null ? depth0.copyright : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"copyright","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n        </div>\n    </div>\n</div>\n";
},"useData":true});

return this["JST"];

});