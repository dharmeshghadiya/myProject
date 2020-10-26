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
            url: APP_URL + '/getCommission',
            type: 'GET',
        },
        columns: [
            {data: 'company_name', name: 'company_name'},
            {data: 'due_balance', name: 'due_balance'},
            {data: 'transferred_balance', name: 'transferred_balance'},
            {data: 'last_transfer_date', name: 'last_transfer_date'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function () {
            funTooltip()
        },
        language: {
            processing: '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only">Loading...</span></div>'
        },
        order: [[3, 'DESC']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
    })

    $(document).on('click', '.status-change', function () {
        const value_id = $(this).data('id')
        const status = $(this).data('status')

        swal({
            title: status,
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
                loaderView()
                $.ajax({
                    type: 'post',
                    url: APP_URL + '/commissionStatusChange',
                    data: {
                        value_id: value_id,
                        status: status,
                    },
                    success: function (data) {
                        successToast(data.message, 'success');
                        table.draw()
                        loaderHide();
                    }, error: function (data) {
                        console.log('Error:', data)
                    }
                })
            }
        });
    })


    $(document).on('click', '.details-single', function () {
        const value_id = $(this).data('booking-id')
        let effect = $(this).attr('data-effect');

        $.ajax({
            type: 'post',
            url: APP_URL + '/getBookingDetails',
            dataType: 'json',
            data: {
                value_id: value_id,
            },
            success: function (data) {
                $("#globalModalTitle").html(data.data.globalModalTitle);
                $("#globalModalDetails").html(data.data.globalModalDetails);
                $('#globalModal').addClass(effect).modal('show');

                loaderHide();
            }, error: function (data) {
                loaderHide();
                console.log('Error:', data)
            }
        })
    })

    $(document).on('click', '.add-transfer', function () {
        const value_id = $(this).data('id')
        const company_name = $(this).data('name')
        let effect = $(this).attr('data-effect');

        $("#globalModalTitle").html(company_name);
        $("#company_id").val(value_id);
        $('#globalModal').addClass(effect).modal('show');

        integerOnly();
    })

    $(document).on('click', '.transfer_amount', function () {
        let company_id = $("#company_id").val();
        let amount = $("#amount").val();

        loaderView();

        $.ajax({
            type: 'post',
            url: APP_URL + '/amountTransfer',
            dataType: 'json',
            data: {
                company_id: company_id,
                amount: amount,
            },
            success: function (data) {
                loaderHide();

                if (data.success == true) {
                    successToast(data.message, 'success');
                    table.draw();
                } else {
                    successToast(data.message, 'error');
                }
            },
            error: function (data) {
                loaderHide();
                console.log('Error:', data)
            }
        })
    })


})


