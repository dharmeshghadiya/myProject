$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    const table = $('#data-table').DataTable();

    $(document).on('change', '#company_id', function () {
        loaderView();
        $.ajax({
            type: 'POST',
            url: APP_URL + '/getBranch',
            data: {company_id: $(this).val()},
            dataType: 'html',
            success: function (data) {
                $("#company_address_id").html(data);
                $("#vehicle").html('');
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    })

    let $form = $('#addEditForm')
    $form.on('submit', function (e) {
        e.preventDefault()
        $form.parsley().validate();
        if ($form.parsley().isValid()) {
            loaderView();
            let formData = new FormData($('#addEditForm')[0])
            $.ajax({
                url: APP_URL + '/categoryVehicleAdd',
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
                        successToast(data.message, 'success');

                        setTimeout(function () {
                            window.location.href = APP_URL + '/categoryRydeDetails/' + $("#category_id").val()
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

    $("#company_id,#company_address_id").select2();
    $('.dropify').dropify();

    $(document).on('change', '#company_address_id', function () {
        loaderView();
        $.ajax({
            type: 'POST',
            url: APP_URL + '/getVehicle',
            data: {
                company_address_id: $("#company_address_id").val(),
                company_id: $("#company_id").val(),
                category_id: $("#category_id").val()

            },
            dataType: 'html',
            success: function (data) {
                $("#vehicle").html(data);
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    });

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
                deleteRecord(value_id,category_id)
            }
        });
    })


$(document).on('change', '#allcheckbox', function () {

if($("#allcheckbox").prop("checked") == true){
   $('input[class=source]').prop('checked', true);
  }else{

      $('input[class=source]').prop('checked', false);
  }

    });


function deleteRecord(value_id,category_id) {
        $.ajax({
            type: 'DELETE',
            url: APP_URL + '/deleteCategoryVehicle' + '/' + value_id,
            success: function (data) {
                successToast(data.message, 'success');
                setTimeout(function () {
                 window.location.href = APP_URL + '/categoryRydeDetails/' + category_id
                        }, 1000);
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }



})

