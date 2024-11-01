<?php
 include('common-function.php'); 

function widg_io_get_review_option_list(){
 $widget_io_get_data = widg_io_get_api_json();
 $response = json_decode($widget_io_get_data);
	$value = [];
	foreach($response as $res){
		$value[__($res->name,  "widg_io_widget"  )] = $res->tag;
	}
	return $value;
}
add_action( 'vc_before_init', 'widg_io_embed_block_for_reviews_omu' );
function widg_io_embed_block_for_reviews_omu() {
		 vc_map( array(
		  "name" => __( "Widg io Widget", "widg_io_widget" ),
		  "base" => "widg_io_widget",
		  "class" => "widg_io_widget",
		  "category" => __( "Content", "widg_io_widget"),
		  'save_always' => true,
		  "params" => array(

			array(
			  "type" => "textfield",
			  "holder" => "div",
			  "class" => "",
			  "heading" => __( "Custom Widg io Widget", "widg_io_widget" ),
			  "param_name" => "custom_widg_io_widget1",
			  "value" => __( "", "widg_io_widget" ),
			  "description" => __( "Description for Widg io Widget.", "widg_io_widget" )
			),

			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Select widget", "widg_io_widget" ),
				"param_name" => "widg_io_widget_tag",
				'value' => widg_io_get_review_option_list(),				
				//"description" => __( "Description for Widg io Widget.", "widg_io_widget" )
			 ),
			 array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Enter Widget Id", "my-text-domain" ),
				"param_name" => "widg_io_widget_id",				 
				"value" => __( "", "my-text-domain" ),
				'save_always' => true,
				//"description" => __( "Enter description.", "my-text-domain" )
			  )
			)
		 ) 
	);
}

add_shortcode( 'widg_io_widget', 'widg_io_bartag_func' );
function widg_io_bartag_func( $atts, $content = null ) { 
	$tag  = $atts['widg_io_widget_tag'];
	$id  = $atts['widg_io_widget_id'];
	$widget_script = widg_io_get_script();
	echo "<$tag widgetid='$id' class='widgio-widget'>'$widget_script'";
}
?>