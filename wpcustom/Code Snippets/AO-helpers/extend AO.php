﻿?php
/*
Plugin Name: Autoptimize Helper
Plugin URI: http://blog.futtta.be/autoptimize
Description: Autoptimize Helper contains some helper functions to make Autoptimize even more flexible
Author: Frank Goossens (futtta)
Version: 0.2
Author URI: http://blog.futtta.be/ 
*/
/* autoptimize_filter_css_datauri_maxsize: change the threshold at which background images are turned into data uri's;

@param $urisize: default size
@return: your own preferred size */// add_filter('autoptimize_filter_css_datauri_maxsize','my_ao_override_dataursize',10,1);
function my_ao_override_dataursize($urisizeIn) {
 return 100000;
}

/* autoptimize_filter_css_datauri_exclude: exclude background images from being turned into data uri's;

@param $imageexcl: default images excluded (empty)
@return: comma-seperated list of images to exclude */// add_filter('autoptimize_filter_css_datauri_exclude','my_ao_exclude_image',10,1);
function my_ao_exclude_image($imageexcl) {
 return "adfreebutton.jpg, otherimage.png";
 }

/* autoptimize_filter_js_defer: change flag added to javascript

@param $defer: default value, "" when forced in head, "defer " when not forced in head
@return: new value */// add_filter('autoptimize_filter_js_defer','my_ao_override_defer',10,1);
function my_ao_override_defer($defer) {
 return $defer."async ";
} 

/* autoptimize_filter_noptimize: stop autoptimize from optimizing, e.g. based on URL as in example

@return: boolean, true or false */// add_filter('autoptimize_filter_noptimize','my_ao_noptimize',10,0);
function my_ao_noptimize() {
 if (strpos($_SERVER['REQUEST_URI'],'no-autoptimize-now')!==false) {
 return true;
 } else {
 return false;
 }
}

/* autoptimize_filter_js_exclude; JS optimization exclude strings, as configured in admin page

@param $exclude: comma-seperated list of exclude strings
@return: comma-seperated list of exclude strings */// add_filter('autoptimize_filter_js_exclude','my_ao_override_jsexclude',10,1);
function my_ao_override_jsexclude($exclude) {
 return $exclude.", customize-support";
}

/* autoptimize_filter_css_exclude: CSS optimization exclude strings, as configured in admin page

@param $exclude: comma-seperated list of exclude strings
@return: comma-seperated list of exclude strings */// add_filter('autoptimize_filter_css_exclude','my_ao_override_cssexclude',10,1);
function my_ao_override_cssexclude($exclude) {
 return $exclude.", recentcomments";
}

/* autoptimize_filter_js_movelast: internal array of what script can be moved to the bottom of the HTML

@param array $movelast
@return: updated array */// add_filter('autoptimize_filter_js_movelast','my_ao_override_movelast',10,1);
function my_ao_override_movelast($movelast) {
 $movelast[]="console.log";
 return $movelast;
 }

/* autoptimize_filter_css_replacetag: where in the HTML is optimized CSS injected

@param array $replacetag, containing the html-tag and the method (inject "before", "after" or "replace")
@return array with updated values */// add_filter('autoptimize_filter_css_replacetag','my_ao_override_css_replacetag',10,1);
function my_ao_override_css_replacetag($replacetag) {
 return array("<head>","after");
 }

/* autoptimize_filter_js_replacetag: where in the HTML is optimized JS injected

@param array $replacetag, containing the html-tag and the method (inject "before", "after" or "replace")
@return array with updated values */// add_filter('autoptimize_filter_js_replacetag','my_ao_override_js_replacetag',10,1);
function my_ao_override_js_replacetag($replacetag) {
 return array("<injectjs />","replace");
 }

/* autoptimize_js_do_minify: do we want to minify? if set to false autoptimize effectively only aggregates, but does not minify

@return: boolean true or false */// add_filter('autoptimize_js_do_minify','my_ao_js_minify',10,1);
function my_ao_js_minify() {
 return false;
 }

/* autoptimize_css_do_minify: do we want to minify? if set to false autoptimize effectively only aggregates, but does not minify

@return: boolean true or false */// add_filter('autoptimize_css_do_minify','my_ao_css_minify',10,1);
function my_ao_css_minify() {
 return false;
 }

/* autoptimize_js_include_inline: do we want AO to also aggregate inline JS?

@return: boolean true or false */// add_filter('autoptimize_js_include_inline','my_ao_js_include_inline',10,1);
function my_ao_js_include_inline() {
 return false;
 }

/* autoptimize_css_include_inline: do we want AO to also aggregate inline CSS?

@return: boolean true or false */// add_filter('autoptimize_css_include_inline','my_ao_css_include_inline',10,1);
function my_ao_css_include_inline() {
 return false;
 }

/* autoptimize_filter_css_defer_inline: what CSS to inline when "defer and inline" is activated

@param $inlined: string with above the fold CSS as configured in admin
@return: updated string with above the fold CSS */// add_filter('autoptimize_filter_css_defer_inline','my_ao_css_defer_inline',10,1);
function my_ao_css_defer_inline($inlined) {
 return $inlined."h2,h1{color:red !important;}";
 }