<?php
	widg_io_get_script();
?>
<div class="review_text">
<?php if(current_user_can('administrator')) {
	if(strpos($_SERVER['REQUEST_URI'], 'widgio-widget') !== false){					
		echo "Please select a widget from the dropdown menu - %%_TAG_NAME_%%";
	}else{
		echo "";
	}
} else {
    echo "";
}
?>
<%%_TAG_URL_%% widgetid="%%_WIDGET_ID_%%" class="widgio-widget"></%%_TAG_URL_%%>
</div>