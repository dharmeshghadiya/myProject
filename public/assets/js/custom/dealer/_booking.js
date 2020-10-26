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
            {data: 'id', name: 'id'},
            {data: 'company_name', name: 'company_name'},
            {data: 'address', name: 'address'},
            {data: 'user_name', name: 'user_name'},
            {data: 'mobile_no', name: 'mobile_no'},
            {data: 'booking_date', name: 'booking_date'},
            {data: 'total_day_rent', name: 'total_day_rent'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: "15%"},
        ],
        drawCallback: function () {
            funTooltip()
        },
        language: {
            processing: '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only">Loading...</span></div>'
        },
        order: [[0, 'ASC']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
    })

    $(document).on('click', '.delete-single', function () {
        const value_id = $(this).data('id')

        swal({
            title: title,
            text: text,
            type: 'warning',
            showCancelButton: true,
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

    let $form = $('#bookingForm')
    $form.on('submit', function (e) {
        e.preventDefault()
        $form.parsley().validate();
        console.log($form.parsley().isValid());
        if ($form.parsley().isValid()) {
            loaderView();
            let formData = new FormData($('#bookingForm')[0])
            $.ajax({
                url: APP_URL + '/getVehicleForBooking',
                type: 'POST',
                dataType: 'html',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loaderHide();


                    $("#vehicle").html(data);


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


    $("#company_address_id").select2();
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


    $('#booking_date').daterangepicker({
        minDate: new Date(),
        locale: {
            format: 'YYYY-MM-DD'
        }

    });

    $(document).on('click', '#get-extra', function () {
        const value_id = $(this).data('id');

        loaderView();
        let effect = $(this).attr('data-effect');
        $('#globalModal').addClass(effect).modal('show');

        $.ajax({
            type: 'GET',
            url: APP_URL + '/getVehcileExtra',
            data: {
                value_id: value_id,
                booking_date: $("#booking_date").val(),
            },
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

    $(document).on('click', '#get-ride', function () {
        loaderView();
        $.ajax({
            url: APP_URL + '/getVehicleForBooking',
            type: 'POST',
            dataType: 'json',
            data: {
                booking_date: $("#booking_date").val(),
                company_address_id: $("#company_address_id").val()
            },
            success: function (data) {
                loaderHide();
                console.log(data.data);
                if (data.success == true) {
                    $("#vehicle").html(data.data);
                } else {
                    successToast(data.message, 'warning')
                }
            },
            error: function (data) {
                loaderHide();
                console.log('Error:', data)
            }
        })
    })

})



