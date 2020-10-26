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
            url: APP_URL + '/becomeADealer',
            type: 'GET',
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'business_name', name: 'business_name'},
            {data: 'business_number', name: 'business_number'},
            {data: 'email', name: 'email'},

            {data: 'mobile_number', name: 'mobile_number'},
            
            {data: 'reason', name: 'reason'},
            {data: 'status', name: 'status'},
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

    

    if ($("#mobile_no").length > 0) {
        let input = document.querySelector("#mobile_no");

            window.intlTelInput(input, {
                separateDialCode: true,
                initialCountry: 'AF'
            });


        //$(".iti__selected-dial-code").text(country_code)
    }

    $(document).on('click', '.accept-status', function () {
        const value_id = $(this).data('id');
        const status = $(this).data('status');
        
        swal({
            title: status,
            text: accept_status_msg,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: acceptButtonText,
            cancelButtonText: cancelButtonText,
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                changeStatus(value_id, status)
            }
        });
    });


   

        $(document).on('click', '.reject-satatus', function () {
        const value_id = $(this).data('id');
        const status = $(this).data('status');
        swal({
                  title: status,
                  text: reject_status_msg,

                  type: "input",
                  showCancelButton: true,
                  confirmButtonClass: "btn-danger",
                  confirmButtonText: rejectButtonText,
                  cancelButtonText: cancelButtonText,
                  closeOnConfirm: false,
                  closeOnCancel: true,
                  animation: "slide-from-top",
                  inputPlaceholder: "Write Reason"
                },
                function(inputValue){
                    
                  if (inputValue === false) return false;

                  if (inputValue === "") {
                    swal.showInputError("You need to write Reason!");
                    return false
                  }
                  
                  changeStatus(value_id,status);
                  swal("", "Dealer has rejected  ", "success");
                });
    });

 function changeStatus(value_id, status) {
        $.ajax({
            type: 'GET',
            url: APP_URL + '/becomeADealerChangeStatus/' + value_id + '/' + status,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }




    function deleteRecord(value_id) {
        $.ajax({
            type: 'DELETE',
            url: APP_URL + '/becomeADealer' + '/' + value_id,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

        $(document).on('click', '.become-a-dealer-details', function () {
        const value_id = $(this).data('id');

        loaderView();
        let effect = $(this).attr('data-effect');
        $('#globalModal').addClass(effect).modal('show');

        $.ajax({
            type: 'GET',
            url: APP_URL + '/becomeAdealerDetails' + '/' + value_id,
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

     integerOnly();
    $('.dropify').dropify();
    
    
})

