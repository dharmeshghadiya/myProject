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
            url: APP_URL + '/booking',
            type: 'GET',
        },
        columns: [
            {data: 'transaction_id', name: 'transaction_id'},
            {data: 'company_name', name: 'company_name'},
            {data: 'address', name: 'address'},
            {data: 'user_name', name: 'user_name'},
            {data: 'mobile_no', name: 'mobile_no'},
            {data: 'booking_date', name: 'booking_date'},
            {data: 'total_day_rent', name: 'total_day_rent'},
            {data: 'status', name: 'status'},
            {data: 'date', name: 'date'},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: "15%"},
        ],
        drawCallback: function () {
            funTooltip()
        },
        language: {
            processing: '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only">Loading...</span></div>'
        },
        order: [[7, 'DESC']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
    })

    $(document).on('click', '.delete-single', function () {
        const value_id = $(this).data('id')

        swal({
            title: title,
            text: text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#067CBA",
            confirmButtonClass: "btn-danger",
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
        console.log($form.parsley().isValid());
        if ($form.parsley().isValid()) {
            loaderView();
            let formData = new FormData($('#addEditForm')[0])
            $.ajax({
                url: APP_URL + '/ryde',
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loaderHide();
                    if (data.success === true) {
                        $form[0].reset()
                        $form.parsley().reset();
                        successToast(data.message, 'success')
                        setTimeout(function () {
                            window.location.href = APP_URL + '/ryde'
                        }, 1000);

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
            url: APP_URL + '/ryde' + '/' + value_id,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

    $("#option").select2({
        placeholder: "Please Select Option"
    });


    $("#make,#body_id,#make,#engine,#door,#fuel,#gearbox,#model,#year,#color").select2();
    $('.dropify').dropify();

    floatOnly();
    integerOnly();


    $(document).on('click', '.booking-details', function () {
        const value_id = $(this).data('id');

        loaderView();
        let effect = $(this).attr('data-effect');
        $('#globalModal').addClass(effect).modal('show');

        $.ajax({
            type: 'GET',
            url: APP_URL + '/BookingDetails' + '/' + value_id,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $("#globalModalTitle").html(data.data.globalModalTitle);
                $("#globalModalDetails").html(data.data.globalModalDetails);
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    })

    $(document).on('click', '.cancel-approved', function () {
        const value_id = $(this).data('id');
        const status = $(this).data('status');
        var confirm_button = '';
        var cancel_booking_title = '';
        var cancel_booking_text_value = '';
        if (status === 'Cancelled') {
            cancel_booking_title = cancel_booking
            cancel_booking_text_value = cancel_booking_text
            confirm_button = yes_cancel_it
        } else {
            cancel_booking_title = reject_booking
            cancel_booking_text_value = reject_booking_text
            confirm_button = yes_reject_it
        }
        swal({
            title: cancel_booking_title,
            text: cancel_booking_text_value,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonColor: "#067CBA",
            confirmButtonText: confirm_button,
            cancelButtonText: cancelButtonText,
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                loaderView();
                $.ajax({
                    type: 'post',
                    url: APP_URL + '/bookingStatusChange',
                    data: {value_id: value_id, status: status},
                    dataType: 'json',
                    success: function (data) {
                        successToast(data.message, 'success');
                        loaderHide();
                    }, error: function (data) {
                        console.log('Error:', data)
                    }
                })
            }
        });


    })

    $(document).on('click', '.edit_button', function () {
        $(".save_button").removeClass('d-none');
        $(".cancel_button").removeClass('d-none');
        $(".edit_button").addClass('d-none');
        $("#adjustment").attr('readonly', false)
    })

    $(document).on('click', '.cancel_button', function () {
        $(".save_button").addClass('d-none');
        $(".cancel_button").addClass('d-none');
        $(".edit_button").removeClass('d-none');
        $("#adjustment").attr('readonly', true)
    })

    $(document).on('click', '.save_button', function () {
        loaderView();
        $.ajax({
            type: 'post',
            url: APP_URL + '/adjustmentSave',
            data: {booking_id: $("#modal_booking_id").val(), adjustment: $("#adjustment").val()},
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    successToast(data.message, 'success');
                    $(".cancel_button").trigger('click');
                    $("#booking-details_"+$("#modal_booking_id").val()).trigger('click');
                } else {
                    successToast(data.message, 'warning');
                }
                loaderHide();
            }, error: function (data) {
                loaderHide();
                console.log('Error:', data)
            }
        })
    })

})



