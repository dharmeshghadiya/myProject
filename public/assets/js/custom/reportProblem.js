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
            url: APP_URL + '/reportProblem',
            type: 'GET',
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'message', name: 'message'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
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



    function deleteRecord(value_id) {
        $.ajax({
            type: 'DELETE',
            url: APP_URL + '/reportProblemDelete' + '/' + value_id,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

    $(document).on('click', '.report-problem-details', function () {
        const value_id = $(this).data('id');

        loaderView();
        let effect = $(this).attr('data-effect');
        $('#globalModal').addClass(effect).modal('show');

        $.ajax({
            type: 'GET',
            url: APP_URL + '/reportProblemDetails'+ '/' + value_id,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $("#globalModalDetails").html(data.data.globalModalDetails);
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    })
})

