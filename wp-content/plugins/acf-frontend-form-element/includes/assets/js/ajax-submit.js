jQuery('body').on('submit','form', function (event) {
  if( jQuery(this).hasClass('acf-form-ajax') ){
    event.preventDefault();
  }
});
  
  form_data = [];
  jQuery('.acf-form-submit .acf-button').on('click', function () {
    if( jQuery(this).closest('form').hasClass('acf-form-ajax') ){
      $form = jQuery(this).closest('form');
      this.blur();
      $form.find('.acf-spinner').css('display','block');
      if( $form.find('.acf-success-message').length ){
        $form.find('.acf-success-message').css('display','none');
      }
      args = {
          form: $form,
          reset: true,
          success: function ($form) {
              let $fileInputs = jQuery('input[type="file"]:not([disabled])', $form)
              $fileInputs.each(function (i, input) {
                  if (input.files.length > 0) {
                      return;
                  }
                  jQuery(input).prop('disabled', true);
              })

              var formData = new FormData($form[0]);

              // Re-enable empty file $fileInputs
              $fileInputs.prop('disabled', false);

              acf.lockForm($form);

              jQuery.ajax({
                url: window.location.href,
                method: 'post',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
              }).done(response => {
                acf.unlockForm($form);
                if(response.success) {
                  $form.find('.acf-spinner').css('display','none');
                  $form.prepend('<div class="acf-notice -success acf-success-message -dismiss"><p class="success-msg">' + response.data.update_message + '</p><a class="acfef-dismiss close-msg acf-notice-dismiss acf-icon -cancel"></a></div>').find('.acfef-submit-button').attr('disabled',false).removeClass('acf-hidden');
                }
              });

          }
      }

      acf.validateForm(args);
  }
});

