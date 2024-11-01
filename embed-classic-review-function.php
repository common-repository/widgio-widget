<?php


$widget_io_basepath = '';
function widget_io_themeslug_enqueue_style(){
    $widget_io_get_data = widg_io_get_api_json();
    $wilist = json_decode($widget_io_get_data);
    $staroData = [];
    foreach ($wilist as $key => $value12) {
        $staroData[] = ['text'=>$value12->name, 'value'=>$value12->tag];
    }
    $widget_io_basepath = json_encode($staroData); ?>
    <script>var embeddReviews = <?php echo $widget_io_basepath; ?>;</script>
    <?php
}

add_action( 'admin_head', 'widget_io_themeslug_enqueue_style' );



// hooks your functions into the correct filters
function widget_io_add_mce_button() {
    // check user permissions
    if ( !current_user_can( 'edit_posts' ) &&  !current_user_can( 'edit_pages' ) ) {
               return;
       }
   // check if WYSIWYG is enabled
   if ( 'true' == get_user_option( 'rich_editing' ) ) {
       add_filter( 'mce_external_plugins', 'widget_io_add_tinymce_plugin' );
       add_filter( 'mce_buttons', 'widget_io_register_mce_button' );
       }
}
add_action('admin_head', 'widget_io_add_mce_button');

// register new button in the editor
function widget_io_register_mce_button( $buttons ) {
    array_push( $buttons, 'wdm_mce_button' );
    return $buttons;
}


// declare a script for the new button
// the script will insert the shortcode on the click event
function widget_io_add_tinymce_plugin( $plugin_array ) {
  $plugin_array['wdm_mce_button'] = plugin_dir_url(__FILE__) .'/includes/js/tinymce_buttons.js';
  return $plugin_array;
}



add_shortcode( 'widg_io_widget_classic', 'widget_io_bartag_func_classic' );
function widget_io_bartag_func_classic( $atts, $content = null ) { 
	$tag  = $atts['widg_io_widget_tag'];
	$id  = $atts['widg_io_widget_id'];
	$widget_script =  widg_io_get_script();
	echo "<$tag widgetid='$id' class='widgio-widget'>'$widget_script'";
}
 
