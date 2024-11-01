( function (blocks, editor, components, i18n, element ) {
    // console.log('components',components);
	var el = wp.element.createElement
	var registerBlockType = wp.blocks.registerBlockType
	var BlockControls = wp.editor.BlockControls
	var AlignmentToolbar = wp.editor.AlignmentToolbar
	var MediaUpload = wp.editor.MediaUpload
	var InspectorControls = wp.editor.InspectorControls
	var TextControl = components.TextControl
	var SelectControl = components.SelectControl 
	var ExternalLink = components.ExternalLink
	var Text = components.Text
	
	
	var ToggleControl = wp.components.ToggleControl
	var ServerSideRender = components.ServerSideRender
	var withState = wp.compose.withState
	
	const feetsArray = [];
	const request = async () => {
		const response = await fetch('https://app.widg.io/data/getwidgetsembedlist');
		const json = await response.json();
			for(var i=0; i<json.length; i++){				  
				feetsArray.push({value:json[i].tag,label:json[i].name});					
			}		
		console.log(json);
	}
	request();
	var github_icon = 
		el( 'svg' , 
			{
			},
			el( 	'path', 
			{
				d: "M19.833 1.127c-0.144-0.129-0.349-0.163-0.527-0.088l-19 8c-0.192 0.081-0.314 0.272-0.306 0.48s0.144 0.389 0.342 0.455l5.658 1.886v5.64c0 0.212 0.133 0.4 0.333 0.471 0.055 0.019 0.111 0.029 0.167 0.029 0.148 0 0.291-0.066 0.388-0.185l2.763-3.401 4.497 4.441c0.095 0.094 0.221 0.144 0.351 0.144 0.042 0 0.084-0.005 0.125-0.016 0.17-0.044 0.305-0.174 0.355-0.343l5-17c0.055-0.185-0.003-0.385-0.147-0.514zM16.13 3.461l-9.724 7.48-4.488-1.496 14.212-5.984zM7 11.746l9.415-7.242-7.194 8.854c-0 0-0 0.001-0.001 0.001l-2.22 2.733v-4.346zM14.256 17.557l-3.972-3.922 8.033-9.887-4.061 13.808z"
			}
			)
		)


	registerBlockType( 'embed-block-for-review/widgio-widget', {
	title: i18n.__( 'Widgio Widget' ),
	description: i18n.__( 'A block to embed a Widgio Widget.Click on following link for more information.' ),
	icon: github_icon,
	keywords: [  i18n.__( 'widgio-widget' ) ],
	category: 'embed',
	attributes: {
		tag_url: {
	    	type: 'string',
		},
		account_id: {
	    	type: 'string',
		},
		darck_mode: {
			type: 'boolean',
			default: false,
		},
	},

	edit: function ( props ) {
		// console.log('props',props);
		var attributes = props.attributes
		var tag_url = props.attributes.tag_url
		var account_id = props.attributes.account_id
		var darck_mode = props.attributes.darck_mode

		return [
			el( 'div', { className: 'components-block-description' },
				el( ServerSideRender, {
					block: 'embed-block-for-review/widgio-widget',
					attributes: props.attributes
				} )
			),
			el(
				InspectorControls,
				{ key: 'inspector' },
				el(
					components.PanelBody, {
						title: i18n.__( 'Widgets' ),
						className: 'block-github-widgio-widget',
						initialOpen: true
					},
					el(
						SelectControl, {
							options: feetsArray,
							label: i18n.__( 'Select the Widget.' ),
							value: tag_url,
							onChange: function ( widgetType ) {
								props.setAttributes( { tag_url: widgetType } )
							}
						}
					),
					el(
						TextControl, {
							type: 'text',
							label: i18n.__( 'Enter the Widget Id' ),
							value: account_id,
							onChange: function ( new_url ) {
								props.setAttributes( { account_id: new_url } )
							}
						}
					),
					el(
						TextControl, {
							type: 'hidden',
							label: i18n.__( 'Please click on following icon for more information regarding widget ID' ),
						}
					),
					//link url
					el(
						ExternalLink, {
							name: i18n.__( 'Enter the Widget Id' ),
							href: 'https://help.widg.io/articles/71107-the-widgio-wordpress-plugin'
						}
					)
					/*el (
						ToggleControl, {
							label: i18n.__( 'Activate Dark Mode' ),
							checked: darck_mode,
							onChange: function ( new_mode ) {
								props.setAttributes( { darck_mode: new_mode } )
							}
						}
					),*/
				)
			),			
		]
	},

	save: () => {
		return null
	}

})

})(
	window.wp.blocks,
	window.wp.editor,
	window.wp.components,
	window.wp.i18n,
	window.wp.element
)