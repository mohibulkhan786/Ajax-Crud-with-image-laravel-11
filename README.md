<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


## Ajax Crud with Image OR File Using Auth in laravel 11

- ✅ In this Post, I will show how to install Bootstrap auth scaffolding in a Laravel 11 application.
- ✅ Laravel provides a UI package for the easy setup of auth scaffolding. Laravel UI offers simple authentication features, including login, registration, password reset, email verification, and password confirmation, using Bootstrap
- In this tutorial, I will provide you with simple steps on how to install Bootstrap 5 and how to create auth scaffolding using Bootstrap 5 in Laravel 11.

- You can clone or download the zipfile after that extracted and paste the pest the folder you want.
- Make the .env file through .env.example
- Run the following commands

````
composer update
````
````
php artisan migrate
````
````
php artisan serve
````

**If you want to install then follow some steps which your help to understand the laravel implementation process**
- Run command and get clean fresh laravel new application.

- ✅ Step 1: Install Laravel 11
- ✅ Step 2: Install Laravel UI
- ✅ Step 3: Create Authentication
- ✅ Step 4: NPM Install Run Dev
- ✅ Step 5: Create Migration
- ✅ Step 6: Install Yajra datatable
- ✅ Step 7: Create Controller Models
- ✅ Step 8: Create Blade
- ✅ Step 9: Create route
- ✅  Run Application


- ✅ Steps 1 First of all, we need to get a fresh Laravel 11 version application using the command below because we are starting from scratch. So, open your terminal or command prompt and run the command:

````
composer create-project "laravel/laravel:^11.0" my-app
````
- Setup the <b>.env file</b>

````
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lara11_ajax_crud_img
DB_USERNAME=root
DB_PASSWORD=
````

- ✅ Steps 2, We need to install the Laravel UI package

````
composer require laravel/ui
````

- ✅ Steps 3,Next, you have to install the Laravel UI package command for creating auth scaffolding using Bootstrap 5. So let's run the command:

````
php artisan ui bootstrap --auth
````
- ✅ Steps 4, Now let's run the below command to install npm:

````
npm install
````
- It will generate CSS and JS min files.
````
npm run build
````
- ✅ Steps 5, Next run the migration command:

````
php artisan migrate
````

- ✅ Steps 6, We need to install the Yajra Datatable composer package for datatable, so you can install using the following command:

````
composer require yajra/laravel-datatables
````

- ✅ Steps 7, We are going to create an AJAX CRUD application for products. So we have to create a migration for the "products" table using Laravel's PHP artisan command:

````
php artisan make:model Product -mcr
````

- Three file created for this command <b>app/Models/Product.php, app/Http/Controller/ProductController.php, database/migrations/2025_04_03_111912_create_products_table.php</b>

- Now add the code in three files database/migrations/2025_04_03_111912_create_products_table.php

````
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('detail');
            $table->text('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
````

````
php artisan migrate
````

- app/Models/Product.php

````
<?php
 
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class Product extends Model
{
    use HasFactory;
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'name', 'detail','image'
    ];
}
````

- app/Http/Controller/ProductController.php
- add images folder in public directory public/images.

````
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use DataTables;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     
        if ($request->ajax()) {
  
           $products = Product::latest()->get();
            return Datatables::of($products)
                    ->addIndexColumn()
                    ->addColumn('image', function ($row) {
                return '<img src="'.$row->image.'" width="50" height="50" class="img-thumbnail"/>';


                   })->addColumn('action', function($row){   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="View" class="me-1 btn btn-info btn-sm showProduct"><i class="fa-regular fa-eye"></i> View</a>';

                           $btn = $btn. '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct"><i class="fa-regular fa-pen-to-square"></i> Edit</a>';
   
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct"><i class="fa-solid fa-trash"></i> Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['image','action'])
                    ->make(true);
        }
        
        return view('products');
    }
       
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            ]);
       

       try {
        $input = $request->all();
        $imagePath = $request->old_file; // Default old image

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if (!empty($request->old_file) && file_exists(public_path($request->old_file))) {
                unlink(public_path($request->old_file));
            }

            // Upload new image
            $image = $request->file('image');
            $imageName = date('YmdHis') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/'), $imageName);
            $imagePath = 'images/' . $imageName;
        }

        Product::updateOrCreate(
            ['id' => $request->product_id],
            [
                'name' => $request->name,
                'detail' => $request->detail,
                'image' => $imagePath,
            ]
        );

        return response()->json(['success' => 'Product saved successfully!']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong! ' . $e->getMessage()], 500);
    }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $product = Product::find($id);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id): JsonResponse
    {
        $product = Product::find($id);
        return response()->json($product);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::find($id);
        $oldFile = $product->image;
        unlink($oldFile);
        Product::find($id)->delete();
      
        return response()->json(['success'=>'Product deleted successfully.']);
    }
}
````

- ✅ Steps 7,In this step we have to create just a blade file. So, we need to create only one blade file.
-  resources/views/products/layout.blade.php

````
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
````

- resources/views/products/products.blade.php

````
<!--extend layout blade file  -->
@extends('layout')

<!--add section-product-blade-file  -->
@section('content')

<!-- start div card with margin-top -->
    <div class="card mt-5">
        <h6 class="card-header"><i class="fa-regular fa-credit-card"></i> Laravel 11 Ajax CRUD Example - AMK Development
        </h6>
        <div class="card-body">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                <a class="btn btn-success btn-sm" href="javascript:void(0)" id="createNewProduct"> <i class="fa fa-plus"></i> Create New Product</a>
            </div>

            <div id="flash_success_msg"></div>

            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th width="60px">No</th>
                        <th>Name</th>
                        <th>Details</th>
                        <th>IMAGE</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

<!-- End of div card-->


<!--this is modal for create and update product data -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="productForm" name="productForm" class="form-horizontal" enctype="multipart/form-data">
                   <input type="hidden" name="product_id" id="product_id">
                   <input type="hidden" name="old_file" id="old_file">
                   @csrf

                    <div class="alert alert-danger flash_error_msg" style="display:none">
                        <ul></ul>
                    </div>

                    <div class="mt-3 form-group">
                        <label for="name" class="col-sm-2 control-label">Name:</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50">
                        </div>
                    </div>
       
                    <div class="mt-3 form-group">
                        <label class="col-sm-2 control-label">Details:</label>
                        <div class="col-sm-12">
                            <textarea id="detail" name="detail" placeholder="Enter Details" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="mt-3 form-group">
                        <div class="col-sm-12">
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                    </div>
                     <div class="col-sm-7">
                        <div id="productImgData"></div>
                       </div>
                   
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-success mt-2" id="saveBtn" value="create"><i class="fa fa-save"></i> Submit
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--End of modal for create and update product data -->


<!--this is modal for Show the product data -->
<div class="modal fade" id="showModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"><i class="fa-regular fa-eye"></i> Show Product</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                 <div class="col-sm-6">
                <p><strong>Name:</strong> <span class="show-name"></span></p>
                <p><strong>Detail:</strong> <span class="show-detail"></span></p>                
                </div>
                 <div class="col-sm-6">
                    <p><strong>Image:</strong> <span class="show-image"></span></p>
            </div>
            </div>
        </div>
    </div>
</div>
<!--this is modal for Show the product data -->

@endsection
<!--end-section OR other-file  -->
````

- ✅ Step 9: Create route Here, we need to add a resource route for the product Ajax CRUD application. So open your "routes/web.php" file and add the following route.

````
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



// product crud resurce route
Route::resource('products', ProductController::class);
````

- Now run the php atisan serve command

````
php artisan serve
````


- ✅ Thanks For watching if you Understand my explanation then support me and my youtube channel [AMK DEVELOPMENT](https://www.youtube.com/@amkdevelopment?sub_confirmation=1)

```
Email:    mohibulkhan15992@gmail.com
Contact:  +917007192298
````













