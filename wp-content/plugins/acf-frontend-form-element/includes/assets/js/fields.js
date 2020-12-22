
acf.add_action('ready_field/type=relationship', function( $el ){	
	$el.find('.acf-button.button-primary').on('click', function(){
        relField = $el;
        
        // popup
        container = showModal($el.data('key'),'500px');

        getForm( $el, 'add_post' );     

    });

    jQuery('body').find($el).on('click','a.edit-post', function(event){
        event.preventDefault();
        event.stopPropagation();

        relField = $el;

        // popup
        container = showModal($el.data('key'),'500px');
        var post = jQuery(this).parent('span.acf-rel-item').data('id');

        getForm( $el, post );     
      
    });
    
});



jQuery('.post-slug-field input').on('input', function() {
    var c = this.selectionStart,
        r = /[`~!@#$%^&*()|+=?;:..’“'"<>,€£¥•،٫؟»«\s\{\}\[\]\\\/]+/gi,
        v = jQuery(this).val();
    if(r.test(v)) {
      jQuery(this).val(v.replace(r,'').toLowerCase());
      c--;
    }
    this.setSelectionRange(c, c);
  }); 

jQuery('body').on('click', 'button.edit-password', function(){
    jQuery(this).addClass('acfef-hidden').siblings('.pass-strength-result').removeClass('acfef-hidden').parents('.acf-field-password').removeClass('edit_password').addClass('editing_password').next('.acf-field-password').removeClass('edit_password');
    jQuery(this).after('<input type="hidden" name="edit_user_password" value="1"/>');
});
jQuery('body').on('click', 'button.cancel-edit', function(){
    jQuery(this).siblings('button.edit-password').removeClass('acfef-hidden').siblings('.pass-strength-result').addClass('acfef-hidden').parents('.acf-field-password').addClass('edit_password').removeClass('editing_password').next('.acf-field-password').addClass('edit_password');
    jQuery(this).siblings('input[name=edit_user_password]').remove();
});

function showModal( $key, $width ){
    var modal = jQuery('#modal_'+$key);
    if(modal.length){
        modal.removeClass('hide').addClass('show');
    }else{
        modal = jQuery('<div id="modal_' + $key + '" class="modal edit-modal show"><div class="modal-content" style="width:' + $width + '"><div class="modal-inner"><span onClick="closeModal(\'' + $key + '\',\'clear\')" class="acf-icon -cancel close"></span><div class="content-container"><div class="loading"><span class="acf-loading"></span></div></div></div></div></div>');
        jQuery('body').append(modal);
    }
    return modal;
}

function getForm( $el, $form_action ){
    var ajaxData = {
        action:		'acfef/fields/relationship/add_form',
        field_key:	$el.data('key'),
        parent_form: $el.parents('form').attr('id'),
        form_action: $form_action,
    };
    // get HTML
    jQuery.ajax({
        url: acf.get('ajaxurl'),
        data: acf.prepareForAjax(ajaxData),
        type: 'post',
        dataType: 'html',
        success: showForm
    });
}

function showForm( html ){	
    
    // update popup
    container.find('.content-container').html(html);  
    acf.do_action('append',container);  
};


jQuery('body').on('submit','form.acfef-form', function (event) {
    event.preventDefault();
    $form = jQuery(this);
    this.blur();
    $form.find('.acf-spinner').css('display','block');

    args = {
        form: $form,
        reset: false,
        success: function ($form) {
            let $fileInputs = jQuery('input[type="file"]:not([disabled])', $form)
            $fileInputs.each(function (i, input) {
                if (input.files.length > 0) {
                    return;
                }
                jQuery(input).prop('disabled', true);
            })

            var formData = new FormData($form[0]);          
            formData.append('action','acfef/form_submit');

            // Re-enable empty file $fileInputs
            $fileInputs.prop('disabled', false);

            acf.lockForm($form);

           jQuery.ajax({
              url: acf.get('ajaxurl'),
              type: 'post',
              data: formData,
              cache: false,
              processData: false,
              contentType: false,
              success: function(response){
                if(response.success) {
                  if( response.data.redirect ){
                    window.location=response.data.redirect;
                  }else{
                    acf.unlockForm($form);

                    successMessage = '<div class="acf-notice -success acf-success-message"><p class="success-msg">' + response.data.update_message + '</p><a class="acfef-dismiss close-msg acf-notice-dismiss acf-icon -cancel small"></a></div>';
                    
                    if(response.data.append){
                      var postData = response.data.append; 
                      if(postData.action == 'edit'){
                          relField.find('div.values').find('span[data-id='+postData.id+']').html(postData.text+'<a href="#" class="acf-icon -minus small dark" data-name="remove_item"></a>');
                          relField.find('div.choices').find('span[data-id='+postData.id+']').html(postData.text);
                      }else{
                          relField.find('div.values ul').append('<li><input type="hidden" name="acf[' + relField.data('key') + '][]" value="' + postData.id + '" /><span data-id="' + postData.id + '" class="acf-rel-item">' + postData.text + '<a href="#" class="acf-icon -minus small dark" data-name="remove_item"></a></span></li>');
                      }                    
                      $form.replaceWith(successMessage);
                      jQuery('body').find('#modal_' + response.data.field_key).delay(400).remove();                    
                    }else{
                      $form.find('.acf-spinner').css('display','none');
                      $form.prepend(successMessage).find('.acfef-submit-button').attr('disabled',false).removeClass('acf-hidden'); 
                    } 
                  }
                }
              }, 
            });  

        }
    }

    acf.validateForm(args);
});
  

  jQuery(document).ready(function(){
    var dynamicValueFields = jQuery('div[data-default]');
    jQuery.each( dynamicValueFields, function( key, value ){
        var fieldElement = jQuery(value);
        var fieldSources = fieldElement.data('default');
        var fieldDynamicValue = fieldElement.data('dynamic_value');
        var fieldInput = fieldElement.find('input[type=text]');
        if( fieldSources.length > 0 ){
            var inputValue = fieldDynamicValue;

            jQuery.each( fieldSources, function( index, fieldName ){
                var fieldData = acfef_get_field_data(fieldName);               
                var sourceInput = acfef_get_field_element(fieldData[0], false);
                inputValue = acfef_get_field_input_value(inputValue, fieldData, sourceInput); 
                var sourceInput = acfef_get_field_element(fieldData[0], true);  
                sourceInput.on('input', function(){
                  var returnValue = fieldDynamicValue;
                  jQuery.each( fieldSources, function( index, fieldName ){
                    var fieldData = acfef_get_field_data(fieldName);               
                    var sourceInput = acfef_get_field_element(fieldData[0], false);
                    returnValue = acfef_get_field_input_value(returnValue, fieldData, sourceInput);
                  });
                  fieldInput.val(returnValue);
                });      
                
            });
            fieldInput.val(inputValue);
            
            function acfef_get_field_input_value(returnValue, fieldData, sourceInput){
              var shortcode = '['+fieldData[0]+']';
              if( sourceInput.val() != '' ){
                var display = sourceInput.val();
                if(fieldData[1] == 'text'){
                  var display = acfef_get_field_text(fieldData[0]);
                }
                returnValue = returnValue.replace(shortcode, display);
              }
              return returnValue;
            }
  
            function acfef_get_field_element(fieldName, all){
                var sourceField = fieldElement.siblings('div[data-name=' + fieldName + ']');
                var sourceInput = sourceField.find('input');
                if(sourceField.data('type') == 'radio'){
                    if(all == true){
                      sourceInput = sourceField.find('input');
                    }else{
                      sourceInput = sourceField.find('input:selected');
                    }
                }
                if(sourceField.data('type') == 'select'){
                    sourceInput = sourceField.find('select');
                }    
                return sourceInput;
            }
            function acfef_get_field_data(fieldName){
              var fieldData = [ fieldName, 'value' ];
              if (~fieldName.indexOf(':')){
                var fieldData = fieldName.split(':');
              }

              return fieldData;
            }
            function acfef_get_field_text(fieldName){
              var sourceField = fieldElement.siblings('div[data-name=' + fieldName + ']');
              if(sourceField.data('type') == 'radio'){
                  sourceInput = sourceField.find('.selected').text();
              }
              if(sourceField.data('type') == 'select'){
                  sourceInput = sourceField.find(':selected').text();
              }    
              return sourceInput;
            }
        }
     });
});

jQuery(document).on('elementor/popup/show',(event, id, instance)=>{acf.do_action('append',jQuery('#elementor-popup-modal-' + id))});var modal;var draft_select;function openModal(modal_number){modal=document.getElementById("modal_"+modal_number);modal.classList.add('show');modal.classList.remove('hide');acf.do_action('append', jQuery("#modal_"+modal_number))};function closeModal(modal_number,clear){modal=jQuery("#modal_"+modal_number);modal.removeClass('show');modal.addClass('hide');if(typeof(clear)!=='undefined'){modal.find('.content-container').html('')}}window.onclick=function(event){if(event.target==modal){modal.classList.remove('show')}};draft_select=document.getElementById('acfef-form-drafts');if(draft_select!=null){ draft_select.onchange=function(){window.location=this.value}}function changeTab(e){jQuery(".multi-step.active:not(.step-hidden)").removeClass("active").addClass("step-hidden"),jQuery(".multi-step.step-"+e).removeClass("step-hidden").addClass("active"),jQuery(".form-tab.active").removeClass("active"),jQuery(".form-tab.step-"+e).addClass("active")}jQuery(".acfef-prev-button").click(function(e){e.preventDefault();var t=jQuery(".acfef-prev-button").siblings("input[name=prev_step_link]").val();document.location.href=t});jQuery(".acfef-reply-link").on("click",function(t){var a=jQuery(this).attr("data-replyform"),e=jQuery(this).attr("data-commentid"),r=jQuery(this).attr("data-postid");jQuery("#"+a).find("input[name=acfef_parent_comment]").val(e),jQuery("#"+a).find("input[name=acfef_parent_post]").val(r)});jQuery(".acfef-draft-button").click(function(a){window.acf.validation.active=!1})