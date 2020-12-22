function checkPasswordStrength( $pass1,$strengthResult,blacklistArray ) {

    var pass1 = $pass1.val();
  

    // Reset the form & meter
    $strengthResult.removeClass( 'weak short bad good strong' );

    // Extend our blacklist array with those from the inputs & site data
    blacklistArray = blacklistArray.concat( wp.passwordStrength.userInputDisallowedList() )

    // Get the password strength
    var strength = wp.passwordStrength.meter( pass1, blacklistArray, pass1 );

    $strengthResult.siblings( 'input.password-strength' ).val(strength);

    // Add the strength meter results
    switch ( strength ) {

    case 2:
    $strengthResult.addClass( 'bad' ).html( pwsL10n.bad );
    break;

    case 3:
    $strengthResult.addClass( 'good' ).html( pwsL10n.good );
    break;

    case 4:
    $strengthResult.addClass( 'strong' ).html( pwsL10n.strong );
    break;

    case 5:
    $strengthResult.addClass( 'short' ).html( pwsL10n.mismatch );
    break;

    default:
    $strengthResult.addClass( 'short' ).html( pwsL10n.short );

    }

    return strength;
}
function checkPasswordsMatch( $confirm,$main,$result ) {

    var pass1 = $main.val();
    var pass2 = $confirm.val();  

    // Reset the form & meter
    $result.removeClass( 'weak strong short' );
    if( pass1 == pass2 ){
        $result.addClass( 'strong' ).html( 'Passwords Match' );
    }else{
        $result.addClass( 'short' ).html( pwsL10n.mismatch );
    }

}
jQuery( document ).ready( function( $ ) {
    // Binding to trigger checkPasswordStrength
    $( 'form' ).on( 'keyup', '.password_main input', function( event ) {
            checkPasswordStrength(
                $(this),  
                $(this).parents('.acf-input-wrap').siblings('.pass-strength-result'),   
                []        // Blacklisted words
            );
        }
    );
    $( 'form' ).on( 'keyup', '.password_confirm input', function( event ) {
        var $main = $(this).parents('.password_confirm').siblings('.password_main').find('input[type=password]');        
            checkPasswordsMatch(
                $(this),   
                $main,
                $(this).parents('.acf-input-wrap').siblings('.pass-strength-result'),   
            );
        }
    );
});
