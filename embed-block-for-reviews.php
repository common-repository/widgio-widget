<?php
/**
 * @link              http://www.widg.io
 * @since             1.1
 * @package           widg.io
 *
 * Plugin Name:       Widg.io social image feed, reviews, live chat, popups and more
 * Plugin URI:        https://wordpress.org/plugins/widg.io
 * Description:       Easily embed Widgio Widgets on your site.
 * Version:           1.1
 * Author:            Widgio
 * Author URI:        https://widg.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       widg.io
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
include('embed-review-function.php');
include('embed-classic-review-function.php'); 



class Widg_io_Widget extends WP_Widget
{
    public function __construct()
    {                      // id_base        ,  visible name
       parent::__construct( 'widg_io_widget', 'Widg io Widget' );
    }
   public function widget( $args, $instance )
    {	
	   	$widget_script = widg_io_get_script();
		$tag ='<'.$instance['text_select'].'  widgetid="'.$instance['text1'].'" class="widgio-widget"></'.$instance['text_select'].'>';
	    echo $args['before_widget'], $widget_script , $tag, $args['after_widget'];

    }

    public function form( $instance )
    {
		
        $text = isset ( $instance['text'] )
            ? esc_textarea( $instance['text'] ) : '';
         printf(
            '<input type="hidden" class="widefat" value= "'.$instance['text1'].'"  id="newid" name="%2$s" value="%3$s" >',
            $this->get_field_id( 'text' ),
            $this->get_field_name( 'text' ),
            $text
        );		

		

		$text_select = isset ( $instance['text_select'] )
            ? esc_textarea( $instance['text_select'] ) : '';
        $get_data = widg_io_get_api_json();
		$response = json_decode($get_data, true);
		//printf($instance['text_select']);
		printf('<label>Select the Widget:</label>');
		printf ('<select style="margin-bottom:14px;"  class="widefat"  id="'.$this->get_field_id('text_select').'" name="'.$this->get_field_name('text_select').'">');		
		
		for($i=0;$i<sizeof($response);$i++){
		if($response[$i]["tag"]==$instance['text_select']){	
		printf ('<option selected value='.$response[$i]["tag"].'>'.$response[$i]["name"].'</option>');
		}else{
			printf ('<option value='.$response[$i]["tag"].'>'.$response[$i]["name"].'</option>');
		}
		}	
        printf ('</select>');
		

		
        $text1 = isset ( $instance['text1'] )
            ? esc_textarea( $instance['text1'] ) : '';
		printf('<label>Enter the Widget Id:</label>');
        printf(
            '<input class="widefat" id="%1$s" name="%2$s" value="%3$s" placeholder="Enter widget ID ">',
            $this->get_field_id( 'text1' ),
            $this->get_field_name( 'text1' ),
            $text1
        );
		
		printf('<p>For more information you can <a href="https://www.widg.io/our-widgets" target="_blank">Click here</a>.</p>');

		
		$text2 = isset ( $instance['text2'] )
            ? esc_textarea( $instance['text2'] ) : '';	
    }
}

function widget_io_review_callAPI($method, $url, $data){
    $args = array(
		'headers' => array(
			'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed exchange;v=b3;q=0.9',
			'accept-encoding' => 'gzip, deflate, br',
			'accept-language' => 'en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7'
		)
	);
	$response = wp_remote_get( $url , $args);
	$body     = wp_remote_retrieve_body( $response );
	return $body;
}

add_action( 'widgets_init', 'widg_io_widget_demo' );

function widg_io_widget_demo()
{
    // Register our own widget.
    register_widget( 'Widg_io_Widget' );
}


class widg_io_embed_block_for_review {

	private function msgdebug ($msg) {
		//$this->msgdebug("PAHT:".plugin_dir_path( __FILE__ ));
		error_log("DEBUG: ".$msg, 0);
	}

	public function __construct() {
		add_action( 'init', array( $this, 'init_wp_register' ) );
	}

	public function init_wp_register() {
		wp_register_script(
			'widgio-template-editor',
			$this->widget_io_plugin_url('includes/js/widgio-template-block.js'),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			$this->widget_io_plugin_file_ver('includes/js/widgio-template-block.js')
		);
		wp_register_style(
			'widgio-template-editor',
			$this->widget_io_plugin_url('includes/css/widgio-template-block-editor.css'),
			array(),
			$this->widget_io_plugin_file_ver('includes/css/widgio-template-block.css')
		);
		wp_register_style(
			'widgio-template',
			$this->widget_io_plugin_url('includes/css/widgio-template-block.css'),
			array(),
			$this->widget_io_plugin_file_ver('includes/css/widgio-template-block.css')
		); 
		$handle = 'wpdocs';
		wp_register_style( $handle, get_stylesheet_directory_uri().'/includes/css/custom.css', array(), '', true );
		register_block_type( 'embed-block-for-review/widgio-widget', array(
			'editor_script'   => 'widgio-template-editor',
			'editor_style'    => 'widgio-template-editor',
			'style'           => 'widgio-template',
			'render_callback' => array( $this, 'widgio_embed_template' ),
			'attributes'      => array(
				'tag_url' => array( 'type' => 'string' ),
				'account_id' => array( 'type' => 'string' ),
				'darck_mode' => array( 'type' => 'boolean' ),
			),
		) );
	}

	private function widget_io_process_template( $template, $data ) {
		ob_start();
		if ( ! locate_template( $this->widget_io_plugin_name() . '/' . $template, true ) ) {
			require 'templates/' . $template;
		}
		return ob_get_clean();
	}

	/* Get Path install plugin */
	private function widget_io_plugin_path(){
		return plugin_dir_path( __FILE__ );
	}

	/* Get Path install plugin and file name. */
	private function widget_io_plugin_file($file){
		if (strlen(trim($file)) > 0) {
			return $this->widget_io_plugin_path() . $file;
		}
		return "";
	}

	/* Get version of the file using modified date. */
	private function widget_io_plugin_file_ver($file) {
		return filemtime( $this->widget_io_plugin_file($file) );
	}

	/* Get folder name plugin */
	private function widget_io_plugin_name() {
		return basename( dirname( __FILE__ ) );
	}

	private function widget_io_plugin_url($file) {
		if (strlen(trim($file)) > 0) {
			return plugins_url( $file, __FILE__ );
		}
		return "";
	}

	private function check_message($message) {
		if ($message == "Not Found") {
			return '<p>' . esc_html__( 'Error: Reposity not found. Please check your URL.', 'embed-block-for-review' ) . '</p>';
		} else {
			return '<p>' . esc_html( sprintf( 'Error: %s', $message ) , 'embed-block-for-review' ) . '</p>';
		}
	}

	public function widgio_embed_template( $attributes ) {
		$tagUrl = trim( $attributes['tag_url'] );
		$account_id = trim( $attributes['account_id'] );
		$darck_mode = (in_array("darck_mode", $attributes) ? $attributes['darck_mode'] : false);
		
		$a_remplace = [];
		$a_remplace['%%_WIDGET_ID_%%'] = $account_id;
		$a_remplace['%%_TAG_URL_%%'] = $tagUrl;
		$a_remplace['%%_TAG_NAME_%%'] = "";

		$a_remplace['%%_WRAPPER_DARK_MODE_%%'] = "ebg-br-wrapper-dark-mode-" . ($darck_mode ? "on" : "off");
		$content = $this->widget_io_process_template('widgio-template.php',null);
		foreach ($a_remplace as $key => $val) {
			$content = str_replace($key, $val, $content);
		}
		return $content;
	}

}

$embed_block_for_review = new widg_io_embed_block_for_review();