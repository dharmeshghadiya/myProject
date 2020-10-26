$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    const table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: APP_URL + '/dealer',
            type: 'GET',
            data: function (d) {
                d.country_id = $("#country_id").val()
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'mobile_no', name: 'mobile_no'},
            {data: 'rydeCount', name: 'rydeCount'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function () {
            funTooltip();
        },
        language: {
            processing: '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only">Loading...</span></div>',
        },
        order: [[0, 'DESC']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
    })

    $(document).on('click', '.delete-single', function () {
        const value_id = $(this).data('id')

        swal({
            title: title,
            text: dealer_destroy,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#067CBA",
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                deleteRecord(value_id)
            }
        });
    })

    let $form = $('#addEditForm')
    $form.on('submit', function (e) {
        e.preventDefault()
        $form.parsley().validate();
        if ($form.parsley().isValid()) {
            loaderView();
            let formData = new FormData($('#addEditForm')[0])
            formData.append('country_code', $(".iti__selected-dial-code").text());
            $.ajax({
                url: APP_URL + '/dealer',
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loaderHide();
                    if (data.success === true) {
                        $form[0].reset()
                        $("#country_id").val('').trigger('change');
                        $form.parsley().reset();
                        successToast(data.message, 'success');
                        setTimeout(function () {
                            window.location.href = APP_URL + '/dealer'
                        }, 1000);

                        $('.dropify-clear').trigger('click')
                    } else if (data.success === false) {
                        successToast(data.message, 'warning')
                    }
                },
                error: function (data) {
                    loaderHide();
                    console.log('Error:', data)
                }
            })
        }
    })

    function deleteRecord(value_id) {
        $.ajax({
            type: 'DELETE',
            url: APP_URL + '/dealer' + '/' + value_id,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

    $('.dropify').dropify();

    integerOnly();


    $(document).on('click', '.status-change', function () {
        const value_id = $(this).data('id');
        const status = $(this).data('status');

        swal({
            title: status,
            text: change_status_msg,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#067CBA",
            confirmButtonText: yes_change_btn,
            cancelButtonText: cancelButtonText,
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                changeStatus(value_id, status)
            }
        });
    });

    function changeStatus(value_id, status) {
        $.ajax({
            type: 'GET',
            url: APP_URL + '/dealerChangeStatus/' + value_id + '/' + status,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

    $("#same_as_contact_details").on('change', function () {
        if ($(this).is(':checked')) {
            $(".same_contact").addClass('d-none');
        } else {
            $(".same_contact").removeClass('d-none');
        }
    });


    if ($("#mobile_no").length > 0) {
        let input = document.querySelector("#mobile_no");
        if (is_edit === 0) {
            window.intlTelInput(input, {
                separateDialCode: true,
            });
        } else {
            window.intlTelInput(input, {
                separateDialCode: true,
                initialCountry: 'AF'
            });

        }
        //$(".iti__selected-dial-code").text(country_code)
    }


    $(document).on('click', '#dealer-search', function () {
        table.draw()
    })

    $(".select2").select2();

    $('#license_expiry_date').datetimepicker({
        format: 'yyyy-mm-dd',
        pickTime: false,
        minView: 2,
        autoclose: true,
    });

    $('.extra_switch').on('click', function () {
        let toggle_id = $(this).attr('id');
        $(this).toggleClass('on');
        if ($(this).hasClass('on')) {
            $("#extra_id_" + toggle_id).attr('readonly', false).removeClass('bg-light').val(1);
        } else {
            $("#extra_id_" + toggle_id).attr('readonly', true).addClass('bg-light').val(2);
        }
    })
})


