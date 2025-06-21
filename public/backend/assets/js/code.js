$(function(){
    $(document).on('click','#delete',function(e){
        e.preventDefault();
        var link = $(this).attr("href");


                  Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete This Data?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = link
                      Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                      )
                    }
                  })


    });

  });

  $(function(){
    $(document).on('click','#ApproveBtn',function(e){
        e.preventDefault();
        var link = $(this).attr("href");


                  Swal.fire({
                    title: 'Are you sure?',
                    text: "Approve This Data?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approved it!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = link
                      Swal.fire(
                        'Approved!',
                        'Your product has been Approved.',
                        'success'
                      )
                    }
                  })


    });

  });


//   $(function(){

//     $(document).on('click', '#approve-selected', function(e) {
//         e.preventDefault();
//         var selectedIds = $('.select-item:checked').map(function() {
//             return $(this).val();
//         }).get();

//         if (selectedIds.length > 0) {
//             Swal.fire({
//                 title: 'Are you sure?',
//                 text: "You are about to approve all selected items. This action cannot be undone.",
//                 icon: 'warning',
//                 showCancelButton: true,
//                 confirmButtonColor: '#3085d6',
//                 cancelButtonColor: '#d33',
//                 confirmButtonText: 'Yes, approve all!'
//             }).then((result) => {
//                 if (result.isConfirmed) {
//                     // Logic to approve the selected items, like sending an AJAX request
//                     $.ajax({
//                         url: "{{ route('purchase.approveAll') }}", // Bulk approval route
//                         method: "POST",
//                         data: {
//                             _token: "{{ csrf_token() }}",
//                             ids: selectedIds
//                         },
//                         success: function(response) {
//                             Swal.fire(
//                                 'Approved!',
//                                 'All selected items have been successfully approved.',
//                                 'success'
//                             );
//                             location.reload(); // Reload to update the statuses
//                         },
//                         error: function(error) {
//                             console.error(error);
//                             Swal.fire(
//                                 'Error!',
//                                 'There was an issue approving the selected items.',
//                                 'error'
//                             );
//                         }
//                     });
//                 }
//             });
//         } else {
//             Swal.fire(
//                 'No Items Selected!',
//                 'Please select at least one item to approve.',
//                 'info'
//             );
//         }
//     });

//   });
