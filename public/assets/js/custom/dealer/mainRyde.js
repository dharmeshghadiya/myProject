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
                url: APP_URL + '/mainRyde',
                type: 'GET',
                data: function (d) {
                    d.company_address_id = $("#companyAddressId").val()
                    d.color = $("#colorFilter").val()
                    d.year = $("#yearFilter").val()
                    d.make = $("#makeFilter").val()
                    d.filter = $("#filter").val()
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'address', name: 'address'},
                {data: 'make', name: 'make'},
                {data: 'model', name: 'model'},
                {data: 'modelYear', name: 'modelYear'},
                {data: 'color', name: 'color'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: "30%"},
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

    $("#filter").click(function () {
        table.draw()
    })
    // $(document).on('change', '#companyAddressId', function () {
    //     table.draw()
    // })
    // $(document).on('change', '#color', function () {
    //     table.draw()
    // })
    // $(document).on('change', '#make', function () {
    //     table.draw()
    // })
    // $(document).on('change', '#year', function () {
    //     table.draw()
    // })

    $(document).on('click', '.delete-single', function () {
        const value_id = $(this).data('id')

        swal({
            title: title,
            text: ryde_destroy,
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
                url: APP_URL + '/mainRyde',
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
                            window.location.href = APP_URL + '/mainRyde'
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
            url: APP_URL + '/vehicle' + '/' + value_id,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

    $("#category").select2({
        placeholder: "Please Select Feature"
    });

    $("#featured").select2({
        placeholder: "Please Select Featured"
    });

    $(document).on('change', '#company_address_id', function () {
        loaderView();
        $.ajax({
            type: 'POST',
            url: APP_URL + '/getExtrasAndSecurityDeposit',
            data: {company_address_id: $(this).val(), vehicle_id: $("#vehicle_id").val()},
            dataType: 'html',
            success: function (data) {
                $("#extra").html(data);

                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    })


    $("#make,#year,#to_year_id,#color,#company_address_id,#companyAddressId,#makeFilter,#yearFilter,#colorFilter,#engine,#gearbox,#fuel,#option").select2();

    $('.dropify').dropify();

    floatOnly();
    integerOnly();


    $(document).on('change', '#make,#year,#to_year_id,#color', function () {
        loaderView();
        $.ajax({
            type: 'POST',
            url: APP_URL + '/getModel',
            data: {brand_id: $("#make").val(), year_id: $("#year").val(),to_year_id: $("#to_year_id").val(), color_id: $("#color").val()},
            dataType: 'html',
            success: function (data) {
                $("#model").html(data);
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    });

    $(document).on('change', '#model', function () {
        loaderView();
        $.ajax({
            type: 'POST',
            url: APP_URL + '/getRyde',
            data: {
                brand_id: $("#make").val(),
                model_id: $("#model").val(),
                year_id: $("#year").val(),
                to_year_id: $("#to_year_id").val(),
                color_id: $("#color").val()
            },
            dataType: 'html',
            success: function (data) {
                $("#ryde").html(data);
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    });

    $(document).on('click', '.status-change', function () {
        const value_id = $(this).data('id');
        const status = $(this).data('status');

        swal({
            title: status,
            text: change_status_msg,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#067CBA",
            confirmButtonClass: "btn-danger",
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

    $(document).on('click', '.duplicate-record', function () {
        const value_id = $(this).data('id');

        swal({
            title: 'Duplicate Record',
            text: 'Are you sure you want Duplicate?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#067CBA",
            confirmButtonClass: "btn-danger",
            confirmButtonText: 'Yes Duplicate it!',
            cancelButtonText: cancelButtonText,
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                duplicateRecord(value_id)
            }
        });
    });

    function duplicateRecord(value_id, status) {
        $.ajax({
            type: 'GET',
            url: APP_URL + '/duplicateRecord/' + value_id,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

    function changeStatus(value_id, status) {
        $.ajax({
            type: 'GET',
            url: APP_URL + '/changeStatus/' + value_id + '/' + status,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

    $(document).on('click', '.vehicle-details', function () {
        const value_id = $(this).data('id');

        loaderView();
        let effect = $(this).attr('data-effect');
        $('#globalModal').addClass(effect).modal('show');

        $.ajax({
            type: 'GET',
            url: APP_URL + '/vehicleDetails' + '/' + value_id,
            dataType: 'json',
            success: function (data) {

                $("#globalModalTitle").html(data.data.globalModalTitle);
                $("#globalModalDetails").html(data.data.globalModalDetails);
                loaderHide();
            }, error: function (data) {
                loaderHide();
                console.log('Error:', data)
            }
        })
    })


    $('.extra_switch').on('click', function () {
        let toggle_id = $(this).attr('id');
        $(this).toggleClass('on');
        if ($(this).hasClass('on')) {
            $("#extra_id_" + toggle_id).attr('readonly', false).removeClass('bg-light').val(1);
            $("#extra_price_" + toggle_id).attr('readonly', false).removeClass('bg-light').val(0);
        } else {
            $("#extra_id_" + toggle_id).attr('readonly', true).addClass('bg-light').val(2);
            $("#extra_price_" + toggle_id).attr('readonly', true).addClass('bg-light').val(0);
        }
    })

    let $form_extra = $('#reportModelForm')
    $form_extra.on('submit', function (e) {
        e.preventDefault()
        $form_extra.parsley().validate();
        if ($form_extra.parsley().isValid()) {
            loaderView();
            let formData = new FormData($('#reportModelForm')[0])
            $.ajax({
                url: APP_URL + '/addReportModel',
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loaderHide();
                    if (data.success === true) {
                        successToast(data.message, 'success');

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

})

function ReportModel(){

    $("#make_id").val($("#make").val());
    $("#model_year_id").val($("#year").val());
    $("#toYearId").val($("#to_year_id").val());
    $("#color_id").val($("#color").val());
}



