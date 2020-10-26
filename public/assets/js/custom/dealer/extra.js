$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })


    let $form = $('#addEditForm')
    $form.on('submit', function (e) {
        e.preventDefault()
        $form.parsley().validate();
        if ($form.parsley().isValid()) {
            loaderView();
            let formData = new FormData($('#addEditForm')[0])
            $.ajax({
                url: APP_URL + '/extra',
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loaderHide();
                    if (data.success === true) {
                        $form.parsley().reset();
                        successToast(data.message, 'success')

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

