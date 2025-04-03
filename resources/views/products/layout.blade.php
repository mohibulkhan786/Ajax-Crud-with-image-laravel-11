<!DOCTYPE html>
<html>
<head>
    <title>Laravel 11 Ajax CRUD Ank Dev</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" ></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>
 <!-- view data in datatables in div container  -->
<div class="container">
  @yield('content')

</div>
      
</body>
      
<script type="text/javascript">
  $(function () {

    /*------------------------------------------
     --------------------------------------------
     Pass Header Token
     --------------------------------------------
     --------------------------------------------*/ 
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
      
    /*------------------------------------------
    --------------------------------------------
    All data Render in data DataTable
    --------------------------------------------
    --------------------------------------------*/
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('products.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'detail', name: 'detail'},
            {data: 'image', name: 'image'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
      
    /*------------------------------------------
    --------------------------------------------
    Click to Button
    --------------------------------------------
    --------------------------------------------*/
    $('#createNewProduct').click(function () {
        $('#saveBtn').val("create-product");
        $('#product_id').val('');
        $('#productForm').trigger("reset");
        $('#modelHeading').html("<i class='fa fa-plus'></i> Create New Product");
        $('#ajaxModel').modal('show');
    });

    /*------------------------------------------
    --------------------------------------------
    Click to Show Button
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.showProduct', function () {
      var product_id = $(this).data('id');
      $.get("{{ route('products.index') }}" +'/' + product_id, function (data) {
          $('#showModel').modal('show');
          $('.show-name').text(data.name);
          $('.show-detail').text(data.detail);
          //$('.show-image').text(data.image);
          $('.show-image').html('<img src="' + data.image + '" alt="Product Image" class="rounded" width="200px" height="200px" />');

      })
    });


    /*------------------------------------------
    --------------------------------------------
    Create Product form data
    --------------------------------------------
    --------------------------------------------*/
    $('#productForm').submit(function(e) {
        e.preventDefault();
 
        let formData = new FormData(this);
        $('#saveBtn').html('Sending...');
  
        $.ajax({
                type:'POST',
                url: "{{ route('products.store') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: (response) => {
                   // alert(response);
                    if(response.success) {
                    $("#flash_success_msg").html('<div class="alert alert-success">'+response.success+'</div>');
                        
                        // 3 sec baad alert flash_massage hide karne ke liye
                        setTimeout(function() {
                            $(".alert").fadeOut();
                        }, 3000);
                    }

                      $('#saveBtn').html('Submit');
                      $('#productForm').trigger("reset");
                      $('#ajaxModel').modal('hide');
                      table.draw();
                },
                error: function(response){
                    //alert(response);
                    $('#saveBtn').html('Submit');
                    $('#productForm').find(".flash_error_msg").find("ul").html('');
                    $('#productForm').find(".flash_error_msg").css('display','block');
                    $.each( response.responseJSON.errors, function( key, value ) {
                        $('#productForm').find(".flash_error_msg").find("ul").append('<li>'+value+'</li>');
                    });
                }
           });
      
    });

      
    /*------------------------------------------
    --------------------------------------------
    Click to Edit Button
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.editProduct', function () {
      var product_id = $(this).data('id');
      $.get("{{ route('products.index') }}" +'/' + product_id +'/edit', function (data) {
          $('#modelHeading').html("<i class='fa-regular fa-pen-to-square'></i> Edit Product");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#product_id').val(data.id);
          $('#old_file').val(data.image);
          $('#name').val(data.name);
          $('#detail').val(data.detail);
          $('#image').val(data.old_file);
          $('#productImgData').html('<img src="' + data.image + '" alt="Product Image" class="rounded" width="200px" height="200px" />');
      })
    });
      
    
      
    /*------------------------------------------
    --------------------------------------------
    Delete Product form data
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.deleteProduct', function () {
     
        var product_id = $(this).data("id");
        confirm("Are You sure want to delete?");
        
        $.ajax({
            type: "DELETE",
            url: "{{ route('products.store') }}"+'/'+product_id,
            success: function (data) {
                table.draw();
                $("#flash_success_msg").html('<div class="alert alert-success">'+data.success+'</div>');
                        
                        // 3 sec baad alert flash_massage hide karne ke liye
                        setTimeout(function() {
                            $(".alert").fadeOut();
                        }, 3000);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
       
  });
</script>
</html>