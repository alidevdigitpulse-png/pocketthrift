<script src="https://js.stripe.com/v3/"></script>
<script>
var stripe = Stripe('{{ env('STRIPE_KEY') }}');

// Create an instance of Elements.
var elements = stripe.elements();
var style = {
    base: {
        color: '#32325d',
        lineHeight: '18px',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
    }
};



var card = elements.create('card', {
    style: style
});
card.mount('#card-element');

card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        $(displayError).show();
        displayError.textContent = event.error.message;
    } else {
        $(displayError).hide();
        displayError.textContent = '';
    }
});



var form = document.getElementById('order-place');

$('#stripe-submit').click(function() {
    stripe.createToken(card).then(function(result) {
        console.log(errorCount);
        var errorCount = checkEmptyFileds();
        if ((result.error) || (errorCount == 1)) {
            // Inform the user if there was an error.
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                $(errorElement).show();
                errorElement.textContent = result.error.message;
            } else {
                $.toast({
                    heading: 'Alert!',
                    position: 'bottom-right',
                    text: 'Please fill the required fields before proceeding to pay',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 5000,
                    stack: 6
                });
            }
        } else {
            // Send the token to your server.
            stripeTokenHandler(result.token);
        }
    });
});



function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('order-place');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    form.submit();
}



function checkEmptyFileds(){
    var errorCount = 0;
    $('form#order-place').find('.form-control').each(function(){
      if($(this).prop('required')){
        if( !$(this).val() ) {
          $(this).parent().find('.invalid-feedback').addClass('d-block');
          $(this).parent().find('.invalid-feedback strong').html('Field is Required');
          errorCount = 1;
        }
      }
    });
    return errorCount;
    }
</script>   