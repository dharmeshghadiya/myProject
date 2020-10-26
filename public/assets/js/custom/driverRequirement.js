$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    const table = $('#data-table').DataTable()

    $(document).on('click', '.delete-single', function () {
        const value_id = $(this).data('id')
        const country_id = $(this).data('country-id')

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
                deleteRecord(value_id, country_id)
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

            $.ajax({
                url: APP_URL + '/driverRequirement/store',
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loaderHide();
                    if (data.success === true) {
                        // $form[0].reset()
                        $form.parsley().reset();
                        successToast(data.message, 'success')

                        setTimeout(function () {
                            window.location.href = APP_URL + '/driverRequirement/' + $("#country_id").val();
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

    function deleteRecord(value_id, country_id) {
        $.ajax({
            type: 'DELETE',
            url: APP_URL + '/driverRequirement/delete' + '/' + value_id + '/' + country_id,
            success: function (data) {
                successToast(data.message, 'success');
                setTimeout(function () {
                    location.reload();
                }, 1000);
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

})

