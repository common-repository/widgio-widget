(function() {
       tinymce.PluginManager.add('wdm_mce_button', function( editor, url ) {
       
           editor.addButton('wdm_mce_button', {
                       text: 'Add Widget',
                        tooltip: 'Add Widget',
                        icon: false,
                       onclick: function() { 
                          //editor.insertContent('[wdm_shortcode]'); 
                          editor.windowManager.open({
                            title: 'Add Widget',
                            body:[
                              {
                                type:'listbox',
                                name:'customwidget_tag',
                                label:'Select Widget',
                                id:'lkjklj',
                                'values':[{text:' -select widget- ', value:''},...embeddReviews],
                              },
                               {
                                  type: 'textbox',
                                  label:'Widget Id',
                                  name: 'customwidget_id',
                                  text: '',
                              },
                            ],
                            
                            width: 374,
                            height: 150,
                            onsubmit: function(e) {
                               var tg = e.data.customwidget_tag;
                               var idg = e.data.customwidget_id;
                               if(tg == '' ||idg == ''){
                                tinymce.activeEditor.windowManager.alert('Please select Widget and Enter id',function(err,data){
                                    jQuery('#mceu_13-button').click();
                                });
                              }else{
                                var cnt = `[widg_io_widget_classic widg_io_widget_tag='${tg}' widg_io_widget_id='${idg}']`;
                                editor.insertContent(cnt);
                              }
                               
                            }
                         });



                      }
             });
       });
})();