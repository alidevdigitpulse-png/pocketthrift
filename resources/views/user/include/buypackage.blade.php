<div class="contact-form-wrapper">
    <form action="{{ route('buy.package') }}" id="buypackage" method="POST">
        @csrf
        <div class="row">
            <ul id="error">
                @if ($errors->any())
                    <p>Please correct the errors below:</p>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endif
            </ul>

            <h5>Buy Subscription</h5>
            <hr>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" disabled>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="package">Package</label>
                    <select name="package" id="package" class="form-control">
                        @foreach ($package as $item)
                            <option data-max={{ $item->no_of_service }} data-price={{ $item->price }}
                                value="{{ $item->id }}">{{ $item->name }}
                                -
                                ${{ $item->price }}/{{ $item->month }} months</option>
                        @endforeach
                        <option value="0">Custom Package</option>
                    </select>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror


                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Service</label>
                    <span style="color: red; float: right;" id="servicepara"></span>
                    <select id="select2" multiple="multiple" name="services[]" style="width: 100%;">
                        @foreach ($service as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-md-12" id="stripediv">
                <label for="">Your Card Number</label>
                <div id="card-element"></div>
                <div id="card-errors"></div>
            </div>
            <div class="col-md-12" id="quote" style="display: none">
                <label for="">Quote for Price for per month</label>
                <input type="number" step="0.01" name="quote" class="form-control">
            </div>

            <div class="col-md-12">
                <div class="form-group text-center">
                    <button type="button" id="stripe-submit" class="btn custom-btn mt-4"><span id="pricespan">Pay Now $
                            {{ $package[0]->price }}</span></button>
                </div>
            </div>
        </div>

    </form>
</div>

@push('css')
    <style>
        span.select2-selection.select2-selection--multiple {
            background: transparent;
            border: 1px solid maroon !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: red !important;
            border: 1px solid #fff !important;
            color: white;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: black !important;
        }

        div#card-element {
            margin-top: 14px;
        }

        input.InputElement.is-invalid.Input {
            color: white !important;
        }

        div#card-element {
            background: white;
            padding: 10px;
        }

        div#card-errors {
            background: red;
            color: white;
            padding: 0 5px;
            margin-top: 5px;
            border-radius: 5px;
        }

        ul#error {
            list-style: none;
        }

        ul#error * {
            background: red;
            color: white;
            margin-bottom: 0;
        }
    </style>
@endpush

@push('js')
    <script src="https://js.stripe.com/v3/"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#select2').select2({
            width: 'resolve', // need to override the changed default
            maximumSelectionLength: 2
        })
    </script>
    <script>
        const alreadySubscribed = () => {
            return new Promise((resolve, reject) => {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    _token: "{{ csrf_token() }}",
                    url: "{{ route('user.check.subscribe') }}",
                    type: "post",
                    data: {
                        packageid: $('#package').val(),
                        service: $('#select2').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {

                            $.toast({
                                heading: 'Alert!',
                                position: 'bottom-right',
                                text: response.message,
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 5000,
                                stack: 6
                            });

                            $('#select2').val(null).trigger('change');
                            resolve(true); // Resolve the Promise with true
                        }
                        else{
                            resolve(false)
                        }

                    },
                    error: function(reject) {
                        if (reject.status === 422) {
                            console.log(reject)
                            var errors = reject.responseJSON.errors;
                            $.each(errors, function(key, val) {
                                $.toast({
                                    heading: 'Missing Input Values',
                                    text: val,
                                    showHideTransition: 'slide',
                                    icon: 'error'
                                })
                            });
                            reject('Error'); // Reject the Promise
                        }
                    }
                })
            });
        }
        $('#package').change(function() {
            $('#select2').val(null).trigger('change');
            if ($(this).val() != 0) {
                $('#quote').slideUp();
                $('#stripediv').slideDown();
                let max_services = $(this).find(':selected').data('max')
                if (Number.isInteger(max_services)) {
                    $('#select2').select2({
                        width: 'resolve', // need to override the changed default
                        maximumSelectionLength: max_services
                    })
                } else if (max_services == 'all') {
                    $('#select2').select2({
                        width: 'resolve', // need to override the changed default
                    })
                    $('#select2').select2('destroy').find('option').prop('selected', 'selected').end().select2();

                };
                $('#servicepara').text(`You can select ${max_services} services`)
                $('#pricespan').text('Pay Now $' + $(this).find(':selected').data('price'))

            } else {
                $('#stripediv').slideUp();
                $('#quote').slideDown();
                $('#select2').select2({
                    width: 'resolve', // need to override the changed default
                })
                $('#pricespan').text('Quote Now');
            }
        })
    </script>
    <script>
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');

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

        var form = document.getElementById('buypackage');

        $('#stripe-submit').click(async function(e) {
            alreadySubscribed().then(response => {
                if (response === false) {
                    if ($('#package').find(':selected').val() != undefined) {
                        console.log($('#package').find(':selected').val());
                        if ($('#package').find(':selected').val() == 0) {


                            var form = document.getElementById('buypackage');
                            form.submit();

                        } else {

                            stripe.createToken(card).then(function(result) {
                                var errorCount = checkEmptyFileds();
                                if ((result.error) || (errorCount == 1)) {
                                    // Inform the user if there was an error.
                                    if (result.error) {
                                        var errorElement = document.getElementById(
                                            'card-errors');
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
                                    stripeTokenHandler(result.token);
                                }
                            });
                        }

                    } else {
                        $.toast({
                            heading: 'Alert!',
                            position: 'bottom-right',
                            text: 'Services might not been selected',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 5000,
                            stack: 6
                        });
                    }
                }
            }).catch(error => {
                console.log(error); // Logs 'Error' if the AJAX request failed
            });




        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('buypackage');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            form.submit();
        }

        function cash() {
            if (checkEmptyFileds() == 0) {

                $('input[name="payer_id"]').val('');
                $('input[name="payment_method"]').val('cash');
                $('#formsubmit').submit();
            }

        }


        function checkEmptyFileds() {
            var errorCount = 0;
            $('form#buypackage').find('input, select').each(function() {
                if ($(this).prop('required')) {
                    if (!$(this).val()) {
                        $(this).parent().find('.invalid-feedback').addClass('d-block');
                        $(this).parent().find('.invalid-feedback strong').html('Field is Required');
                        errorCount = 1;
                    }
                }
            });
            return errorCount;
        }
    </script>
@endpush
