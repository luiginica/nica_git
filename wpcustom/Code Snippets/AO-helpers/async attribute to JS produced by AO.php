﻿/*To add the async attribute to the script produced by Autoptimize
Use https://wordpress.org/plugins/code-snippets/*/

add_filter('autoptimize_filter_js_defer','my_ao_override_defer',10,1);
function my_ao_override_defer($defer) {
 return $defer."async ";
}
