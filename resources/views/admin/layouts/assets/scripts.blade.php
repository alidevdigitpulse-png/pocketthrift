<!-- |==================================== JQuery ==================================================| -->
<script src="https://code.jquery.com/jquery-3.6.3.slim.min.js"
    integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script>
<!-- |==================================== JQuery ==================================================| -->
<script src="{{ asset('admin/js/vendors.min.js') }}"></script>
<script src="{{ asset('admin/js/template.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
    integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.js"
    integrity="sha512-6rE6Bx6fCBpRXG/FWpQmvguMWDLWMQjPycXMr35Zx/HRD9nwySZswkkLksgyQcvrpYMx0FELLJVBvWFtubZhDQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('admin/js/dashboard.js') }}"></script>
<script src="{{ asset('admin/js/datatables.min.js') }}"></script>
<script src="{{ asset('admin/js/data-table.js') }}"></script>
<script src="{{ asset('admin/js/script.js') }}"></script>
<script src="{{ asset('admin/js/toastr.js') }}"></script>
<script>
    $(document).ready(function() {
        if ($('.dropify').length != 0) {
            $('.dropify').dropify();
        }
    })
    $(function() {
        "use strict";
        $('.editor').summernote();
    });

    @if (\Session::has('success'))
        swal("{{ Session::get('success') }}", "", "success", {
            timer: 3000,
        })
    @endif
    @if (\Session::has('error'))
        swal("{{ Session::get('error')['heading'] }}", "{{ Session::get('error')['message'] }}", "error", {
            timer: 3000,
        })
    @endif
</script>

<script>
    $(function() {
        $(document).ready(function() {
            $('.progress').hide();
            $('#file-upload').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    beforeSend: function() {
                        $('.box-footer').hide();
                        $('.progress').slideDown();
                        var percentage = '0';
                    },
                    xhr: function() {
                        // Handle progress if needed
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(event) {
                            if (event.lengthComputable) {
                                var percentage = (event.loaded / event
                                        .total) *
                                    100;
                                $('#progressBar').css('width', percentage +
                                    '%')
                                $('#progressBar').text(percentage + '%');


                                // Update your UI with the percentage if needed
                            }
                        }, false);
                        return xhr;
                    },
                    method: 'post',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    headers: {
                        enctype: 'multipart/form-data'
                    },
                    url: $(this).attr('action'),
                }).done(function(response) {

                    swal("SUCCESS!", "Data Updated Successfully", "success");
                }).fail(function(jqxhr, textStatus, error) {
                    console.log(error);
                    swal("ERROR!", error, "error");
                }).always(function() {
                    // This code will run regardless of success or failure
                    loaderHide();
                    $('.box-footer').show();
                    $('.progress').slideUp();
                });
            });



        });




    });
    var i = 0;

    function progress() {
        if (i == 0) {
            i = 1;
            var elem = document.getElementById("progressBar");
            var width = 10;
            var id = setInterval(frame, 10);

            function frame() {
                if (width >= 100) {
                    clearInterval(id);
                    i = 0;
                } else {
                    width++;
                    elem.style.width = width + "%";
                    elem.innerHTML = width + "%";
                }
            }
        }
    }
</script>
