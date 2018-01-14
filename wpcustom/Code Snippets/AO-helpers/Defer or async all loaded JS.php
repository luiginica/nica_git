/*Function to defer or asynchronously load scripts*/function js_async_attr($tag){

# Do not add defer or async attribute to these scripts
$scripts_to_exclude = array('script1.js', 'script2.js', 'script3.js');

foreach($scripts_to_exclude as $exclude_script){
 if(true == strpos($tag, $exclude_script ) )
 return $tag; 
}

# Defer or async all remaining scripts not excluded above
return str_replace( ' src', ' defer="defer" src', $tag );
}
add_filter( 'script_loader_tag', 'js_async_attr', 10 );