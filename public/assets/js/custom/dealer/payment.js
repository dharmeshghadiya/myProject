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
            url: APP_URL + '/payment',
            type: 'GET',
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'amount', name: 'amount'},
            {data: 'type', name: 'type'},
            {data: 'status', name: 'status'},
            {data: 'date', name: 'date'},
        ],
        drawCallback: function () {
            funTooltip()
        },
        language: {
            processing: '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only">Loading...</span></div>'
        },
        order: [[4, 'DESC']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
    })

    $(document).on('click', '.withdraw', function () {

        $.ajax({
            type: 'get',
            url: APP_URL + '/withdraw',
            success: function (data) {
                $(".withdraw").addClass('d-none');
                $(".total_balance").html(0);
                if (data.success === true) {
                    successToast(data.message, 'success');
                } else {
                    successToast(data.message, 'error');
                }
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    })

    $('.dropify').dropify();


    integerOnly();


    $(document).on('click', '.booking-details', function () {
        const value_id = $(this).data('id');

        loaderView();
        let effect = $(this).attr('data-effect');
        $('#globalModal').addClass(effect).modal('show');

        $.ajax({
            type: 'GET',
            url: APP_URL + '/BookingDetails'+ '/' + value_id,
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




