<!-- |==================================== JQuery ==================================================| -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<!-- |==================================== JQuery ==================================================| -->
<script src="{{ asset('admin/js/vendors.min.js') }}"></script>
<script src="{{ asset('admin/js/template.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
    integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.js"
    integrity="sha512-6rE6Bx6fCBpRXG/FWpQmvguMWDLWMQjPycXMr35Zx/HRD9nwySZswkkLksgyQcvrpYMx0FELLJVBvWFtubZhDQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
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
    $(document).ready(function () {
        if ($('.dropify').length != 0) {
            $('.dropify').dropify();
        }
    })
    $(function () {
        "use strict";

        // Define Custom Button
        var ButtonButton = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-square"/> Button',
                tooltip: 'Insert Custom Button',
                click: function () {
                    var text = prompt('Enter Button Text:', 'Click Here');
                    if (text === null) return; // Cancelled

                    var url = prompt('Enter URL:', 'https://');
                    if (url === null) return; // Cancelled

                    // Ask for no-follow using sweet alert since browser prompts don't have checkboxes
                    var wrapper = document.createElement('div');
                    wrapper.innerHTML = '<div style="margin-top: 15px; text-align: center;"><input type="checkbox" id="btnNofollowCheck" style="transform: scale(1.3); margin-right: 8px; vertical-align: middle; cursor: pointer;"> <label for="btnNofollowCheck" style="font-size: 16px; vertical-align: middle; cursor: pointer; margin-bottom: 0;">Add rel="nofollow"</label></div>';
                    
                    // Save range so the editor doesn't lose current cursor position when swal opens
                    context.invoke('editor.saveRange');

                    swal({
                        text: "Additional Options",
                        content: wrapper,
                        buttons: {
                            cancel: "Cancel",
                            confirm: "Insert Button"
                        }
                    }).then(function (isConfirm) {
                        if (!isConfirm) {
                            return; // Cancelled
                        }

                        var isNofollow = document.getElementById('btnNofollowCheck').checked;
                        var relAttr = isNofollow ? ' rel="nofollow"' : '';

                        // Create button node with center alignment
                        var btnHtml = '<div style="text-align: center;"><a href="' + url + '" target="_blank"' + relAttr + ' class="btn btn-primary" style="display: inline-block; padding: 10px 20px; background-color: #cf5103; color: #fff; text-decoration: none; border-radius: 5px; border: none;">' + text + '</a></div>&nbsp;';

                        // Restore focus and insert content
                        context.invoke('editor.restoreRange');
                        context.invoke('editor.focus');
                        context.invoke('editor.pasteHTML', btnHtml);
                    });
                }
            });
            return button.render();
        }

        // Define Custom Table Button
        var TableButton = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-table"/> Table',
                tooltip: 'Insert Table',
                click: function () {
                    var rows = prompt('Number of rows:', '3');
                    if (rows === null || isNaN(rows) || rows <= 0) return; // Cancelled or invalid input

                    var cols = prompt('Number of columns:', '3');
                    if (cols === null || isNaN(cols) || cols <= 0) return; // Cancelled or invalid input

                    var tableHtml = '<table class="table table-bordered" style="width: 550px;">\n';
                    for (var i = 0; i < rows; i++) {
                        tableHtml += '<tr>\n';
                        for (var j = 0; j < cols; j++) {
                            tableHtml += '  <td><br></td>\n';
                        }
                        tableHtml += '</tr>\n';
                    }
                    tableHtml += '</table><br>';

                    // Insert content at the current cursor position
                    context.invoke('editor.insertNode', $(tableHtml)[0]);
                }
            });
            return button.render();
        }

        // Define Vertical Align Top Button
        var VerticalAlignTopButton = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-arrow-up"/>',
                tooltip: 'Align Top',
                click: function () {
                    var node = $(window.getSelection().focusNode);
                    var td = node.closest('td, th');
                    if (td.length) {
                        td.css('vertical-align', 'top');
                    }
                }
            });
            return button.render();
        }

        // Define Vertical Align Middle Button
        var VerticalAlignMiddleButton = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-minus"/>',
                tooltip: 'Align Middle',
                click: function () {
                    var node = $(window.getSelection().focusNode);
                    var td = node.closest('td, th');
                    if (td.length) {
                        td.css('vertical-align', 'middle');
                    }
                }
            });
            return button.render();
        }

        // Define Vertical Align Bottom Button
        var VerticalAlignBottomButton = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-arrow-down"/>',
                tooltip: 'Align Bottom',
                click: function () {
                    var node = $(window.getSelection().focusNode);
                    var td = node.closest('td, th');
                    if (td.length) {
                        td.css('vertical-align', 'bottom');
                    }
                }
            });
            return button.render();
        }

        $('.editor').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['customTable']], // Use custom table button
                ['valign', ['valignTop', 'valignMiddle', 'valignBottom']],
                ['insert', ['link', 'picture', 'video', 'customButton']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            buttons: {
                customButton: ButtonButton,
                customTable: TableButton,
                valignTop: VerticalAlignTopButton,
                valignMiddle: VerticalAlignMiddleButton,
                valignBottom: VerticalAlignBottomButton
            }
        });
    });

    @if (\Session::has('success'))
        swal("{{ Session::get('success') }}", "", "success", {
            timer: 3000,
        })
    @endif
    @if (\Session::has('error'))
        @php
            $error = Session::get('error');
            $heading = is_array($error) ? $error['heading'] : 'Error';
            $message = is_array($error) ? $error['message'] : $error;
        @endphp
        swal("{{ $heading }}", "{{ $message }}", "error", {
            timer: 3000,
        })
    @endif
</script>

<script>
    $(function () {
        $(document).ready(function () {
            $('.progress').hide();
            $('#file-upload').submit(function (e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    beforeSend: function () {
                        $('.box-footer').hide();
                        $('.progress').slideDown();
                        var percentage = '0';
                    },
                    xhr: function () {
                        // Handle progress if needed
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (event) {
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
                }).done(function (response) {

                    swal("SUCCESS!", "Data Updated Successfully", "success");
                }).fail(function (jqxhr, textStatus, error) {
                    console.log(error);
                    swal("ERROR!", error, "error");
                }).always(function () {
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