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
        timePicker: true,
        timePicker24Hour: true,
        minDate: new Date(),
        locale: {
            format: 'YYYY-MM-DD H:mm'
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

    $(document).on('change', '#company_address_id', function () {
        loaderView();
        $.ajax({
            url: APP_URL + '/getVehicleForBooking',
            type: 'POST',
            data: {
                booking_date: $("#booking_date").val(),
                company_address_id: $("#company_address_id").val()
            },
            success: function (data) {
                if (data.success === true) {
                    loaderHide();
                    $("#ryde-display").html(data);
                } else {
                    loaderHide();
                    $("#ryde-display").html(data);
                   // successToast(data.message, 'success')
                }
            },
            error: function (data) {
                loaderHide();
                //console.log('Error:', data)
            }
        })
    })

    $(document).on('click', '.addBooking', function () {
        loaderView();
        const value_id = $(this).data('id');
        const car_name = $(this).data('name');
        const country_id = $(this).data('country-id');

        let effect = $(this).attr('data-effect');
        $('#bookingModal').addClass(effect).modal('show');

        $.ajax({
            type: 'POST',
            url: APP_URL + '/getVehicleDetails',
            data: {
                value_id: value_id,
                company_address_id: $("#company_address_id").val(),
                booking_date: $("#booking_date").val(),
                country_id: country_id,
            },
            dataType: 'json',
            success: function (data) {
                //console.log(data.data.modalData);
                $("#booking-modal-title").html(car_name);
                $("#booking-modal-body").html(data.data.modalData);
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data);
                loaderHide();
            }
        })
    })


    $(document).on('change', '.extra_check_box', function () {
        var no_days = $("#no_days").val();
        var tax_percentage = $("#tax_percentage").val();
        var sub_total = $("#sub_total").val();
        loaderView();
        var extra_val = [];
        $('.extra_check_box:checked').each(function () {
            extra_val.push($(this).val());
        });

        loaderHide();


        getPricing(extra_val, no_days, tax_percentage, sub_total);
    })

    $(document).on('click', '#bookNow', function () {
        let $name = $('#name');
        let $email = $('#email');
        let $country_code = $('#country_code');
        let $mobile_no = $('#mobile_no');
        let $message = $("#message");
        let sub_total = $("#sub_total").val();
        let total_tax = $("#total_tax").val();
        let total_amount = $("#total_amount").val();
        let tax_percentage = $("#tax_percentage").val();
        let vehicle_id = $("#vehicle_id").val();
        let start_date = $("#start_date").val();
        let end_date = $("#end_date").val();
        let total_day_rent = $("#total_day_rent").val();

        let company_address_id = $("#modal_company_address_id").val();
        //let extra = $("#extra").val();
        var extra_val = [];
        $('.extra_check_box:checked').each(function () {
            extra_val.push($(this).val());
        });

        loaderView();
        $.ajax({
            type: 'POST',
            url: APP_URL + '/addBooking',
            data: {
                name: $name.val(),
                email: $email.val(),
                company_address_id: company_address_id,
                country_code: $country_code.val(),
                mobile_no: $mobile_no.val(),
                sub_total: sub_total,
                total_amount: total_amount,
                total_tax: total_tax,
                tax_percentage: tax_percentage,
                vehicle_id: vehicle_id,
                start_date: start_date,
                end_date: end_date,
                total_day_rent: total_day_rent,
                message: $message.val(),
                extra_val: extra_val,
            },
            dataType: 'json',
            success: function (data) {
                if (data.success === true) {
                    successToast(data.message, 'success');
                    $name.val('');
                    $email.val('');
                    $country_code.val('');
                    $mobile_no.val('');
                    $message.val('');
                } else {
                    successToast(data.message, 'warning');
                }

                $('#bookingModal').modal('hide');
                $("#company_address_id").trigger('change');
                loaderHide();
            }, error: function (data) {
                // console.log('Error:', data);
                loaderHide();
            }
        })

    })


})

function getPricing(extra_val, no_days, tax_percentage, sub_total) {
    $.ajax({
        type: 'POST',
        url: APP_URL + '/getFinalAmount',
        data: {
            extra_val: extra_val,
            no_days: no_days,
            tax_percentage: tax_percentage,
            sub_total: sub_total,
        },
        dataType: 'json',
        success: function (data) {
            $("#extra").val(data.final_extra_price);
            $("#total_tax").val(data.total_tax);
            $("#total_amount").val(data.total);
            loaderHide();
        }, error: function (data) {
            console.log('Error:', data);
            loaderHide();
        }
    })
}
