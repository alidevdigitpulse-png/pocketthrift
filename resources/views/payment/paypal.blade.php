<script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&components=buttons&disable-funding=paylater,credit,card"></script>
<script>
var paypalItemsDetails = [];
    
$('.productOrderSummary_item').each(function(){
    var unitAmountDetails 
    console.log($(this).find('.price').text());
    paypalItemsDetails.push({
        name:  $(this).find('.name').text().trim(),
        unit_amount: { currency_code : "USD", value : parseFloat($(this).find('.price').text().trim().replace(/\$/g,'')) / parseInt($(this).find('.quantity').text()) },
        quantity: parseInt($(this).find('.quantity').text())
    });
});

console.log(paypalItemsDetails);
console.log({
  "purchase_units": [{
      "amount": {
          "currency_code": "USD",
          "value": parseFloat(),
          "breakdown": {
              "item_total": {
                  /* Required when including the `items` array */
                  "currency_code": "USD",
                  "value": parseFloat(),
              },
               /*"discount": {
                  "currency_code": "USD",
                  "value": Math.abs($('#avail_discount').text().replace(/\$/g,'')) + Math.abs($('#display-dicount').text().replace(/\$/g,''))
              }, 
                "shipping": {
                  "currency_code": "USD",
                  "value":  shipping_amount
              },  
               "tax_total": {
                  "currency_code": "USD",
                  "value": state_tax
              }, */ 

          }
      },
      "items": paypalItemsDetails
  }]
  // purchase_units: [{
  //     amount: {
  //         value: parseFloat('90') + shipping_amount + state_tax,
  //     }
  // }]
});

var paypalActions;
paypal.Buttons({
    style: {
        label: 'checkout',
        size:  'responsive',  
        shape: 'rect',    
        color: 'gold'   
    },
    env: "production", //production
    
    createOrder: function(data, actions) {
        // Set up the transaction
        return actions.order.create({
            "purchase_units": [{
                "amount": {
                    "currency_code": "USD",
                    "value": parseFloat(),
                    "breakdown": {
                        "item_total": {
                            /* Required when including the `items` array */
                            "currency_code": "USD",
                            "value": parseFloat(),
                            
                        },
                         /*"discount": {
                            "currency_code": "USD",
                            "value": Math.abs($('#avail_discount').text().replace(/\$/g,'')) + Math.abs($('#display-dicount').text().replace(/\$/g,''))
                        }, 
                          "shipping": {
                            "currency_code": "USD",
                            "value":  shipping_amount
                        },  
                         "tax_total": {
                            "currency_code": "USD",
                            "value": state_tax
                        }, */ 

                    }
                },
                "items": paypalItemsDetails
            }]
            // purchase_units: [{
            //     amount: {
            //         value: parseFloat('90') + shipping_amount + state_tax,
            //     }
            // }]
        });
    },
    onClick: function(data, actions) {
        if (checkEmptyFileds() == 1) {
            $.toast({
                heading: 'Alert!',
                position: 'bottom-right',
                text: 'Please fill the required fields before proceeding to pay',
                loaderBg: '#ff6849',
                icon: 'error',
                hideAfter: 5000,
                stack: 6
            });

            return actions.reject();
        } else {
            return actions.resolve();
        }
    },

    onApprove(data, actions) {
     return actions.order.capture()
                .then(function (details) {
                
                    if(details['status'] == "COMPLETED")
                    {
                        toastr.success('Payment Authorized! Thank You For Booking')
                        $('input[name="payment_status"]').val('Completed');
                        $('input[name="payment_id"]').val(data.orderID);
                        $('input[name="payer_id"]').val(data.payerID);
                        $('input[name="order_total"]').val(parseFloat());
                        // {{--  $('input[name="shipping_tax"]').val(shipping_amount);  --}}
                        $('input[name="payment_method"]').val('paypal');
                        $('#order-place').submit()
                    }
                    else{
                        toastr.error('Something went wrong');
                    }

                });
        
        // $('input[name="payment_status"]').val('Completed');
        // $('input[name="payment_id"]').val(data.orderID);
        // $('input[name="payer_id"]').val(data.payerID);
        // $('input[name="order_total"]').val(parseFloat('90') + parseFloat(shipping_amount));
        // $('input[name="shipping_tax"]').val(shipping_amount);
        // $('input[name="payment_method"]').val('paypal');
        // $('#order-place').submit();
    }
}).render('#paypal-button-container-popup');

$('#different_address').click(function(){
    if($('#different_address').is(":checked")){
        $('#shipping_info').show();
    }else{
        $('#shipping_info').hide();
    }
});


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