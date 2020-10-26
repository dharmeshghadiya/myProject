$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    const table = $('#data-table').DataTable();

    let $vForm = $('#vehicleNotAvailableForm')
    $vForm.on('submit', function (e) {
        e.preventDefault()
        $vForm.parsley().validate();
        console.log($vForm.parsley().isValid());
        if ($vForm.parsley().isValid()) {
            loaderView();
            let formData = new FormData($('#vehicleNotAvailableForm')[0])
            $.ajax({
                url: APP_URL + '/updateVehicleNotAvailable',
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loaderHide();
                    if (data.success === true) {
                        $vForm[0].reset()
                        $vForm.parsley().reset();
                        successToast(data.message, 'success')
                         setTimeout(function () {
                            window.location.href = APP_URL + '/vehicleNotAvailable/'+data.id
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

    $(document).on('click', '.delete-single', function () {
        const value_id = $(this).data('id');
        const vehicle_id = $(this).data('vehicle_id');

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
                deleteRecord(value_id,vehicle_id)
            }
        });
    })

    function deleteRecord(value_id,vehicle_id) {
        $.ajax({
            type: 'GET',
            url: APP_URL + '/vehicleNotAvailableDelete' + '/' + value_id + "/" + vehicle_id,
            success: function (data) {
                successToast(data.message, 'success');
               window.location.href = APP_URL + '/vehicleNotAvailable/'+data.id
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }


    funTooltip();


    let $fancybox = $(".fancybox");

    if ($fancybox.length > 0) {
        $fancybox.fancybox();
    }

    $('#start_date').datetimepicker({
        defaultDate: "+1w",
        format: 'yyyy-mm-dd hh:ii',
        minDate: 0,  // disable past date
        minTime: 0, // disable past time
        startDate: new Date(),
        autoclose: true,
    });
    $('#end_date').datetimepicker({
        defaultDate: "+1w",
        format: 'yyyy-mm-dd hh:ii',
        minDate: 0,  // disable past date
        minTime: 0, // disable past time
        startDate: new Date(),
        autoclose: true,
    });
})




