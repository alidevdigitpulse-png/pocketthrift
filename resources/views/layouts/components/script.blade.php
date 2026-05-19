
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
    integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous" defer></script>
    <script src="{{ asset('admin/js/toastr.js') }}" defer></script>
    <script src="{{ asset('admin/js/script.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>

    $('.contactform').submit(function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            _token: "{{ csrf_token() }}",
            url: "{{ route('inquiry.submit') }}",
            type: "post",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                $('.contactform')[0].reset();
                if (response.status) {
                    toastrShow('Sumbitted',response.message)

                } else {
                    toastrShow('Cannot Submit',response.message)

                }
            }
        });
    })
</script>

<script>
    $(document).ready(function(){
        var count = 1;
        var stock = parseInt($("#input-qty").attr("max"));
        $(".plus").click(function() {
            if (count < stock) {
                count += 1;
                $(".qty-value").val(count);
            }
        else {
            // Show toastr notification if stock is full
            toastr.error('Stock is full');
        }
        });
    

        $(".minus").click(function(){
           if(count > 1)
           {
                count -= 1;
                $(".qty-value").val(count)
           }
        });
        
        var input = document.querySelector('.qty-value');

        if (input) {
            input.addEventListener('input', function() {
                var value = input.value;
                
                value = value.replace(/^0+/, '');
                
                value = value.replace(/\D/g, '');
                if (value.length > 3) {
                    value = value.slice(0, 3);
                }
                input.value = value;
            });
        }  
    $(".qty-value").on('input', function() {
        var value = parseInt($(this).val());
        if (isNaN(value) || value < 1) {
            count = 1;
        } else if (value > stock) {
            count = stock;
            toastr.error('Stock is full');
        } else {
            count = value;
        }
        $(this).val(count);
    });

    });
</script>

<script>
    @if (\Session::has('success'))
        $.toast({
            heading: 'Submitted',
            text: "{{ Session::get('success') }}",
            showHideTransition: 'slide',
            icon: 'success'
        })
    @endif
    @if (\Session::has('error'))
        @php
            $error = Session::get('error');
            $heading = is_array($error) ? $error['heading'] : 'Error';
            $message = is_array($error) ? $error['message'] : $error;
        @endphp
        $.toast({
            heading: '{{ $heading }}',
            text: "{{ $message }}",
            showHideTransition: 'slide',
            icon: 'error'
        })
    @endif
</script>
