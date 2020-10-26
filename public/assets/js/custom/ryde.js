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
            url: APP_URL + '/ryde',
            type: 'GET',
        },
        columns: [
            {data: 'id', name: 'id'},
             {data: 'image', name: 'image'},
            {data: 'brand_name', name: 'brand_name'},
            {data: 'modelYear', name: 'modelYear'},
            {data: 'model_name', name: 'model_name'},
            {data: 'color', name: 'color'},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: "15%"},
        ],
        drawCallback: function () {
            funTooltip()
        },
        language: {
            processing: '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only">Loading...</span></div>'
        },
        order: [[0, 'DESC']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
    })

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




    $("#make,#body_id,#make,#engine,#door,#fuel,#gearbox,#model,#year,#color,#to_year_id").select2();
    $('.dropify').dropify();

    floatOnly();
    integerOnly();


    $(document).on('click', '.ryde-details', function () {
        const value_id = $(this).data('id');

        loaderView();
        let effect = $(this).attr('data-effect');
        $('#globalModal').addClass(effect).modal('show');

        $.ajax({
            type: 'GET',
            url: APP_URL + '/rydeDetails'+ '/' + value_id,
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

})



