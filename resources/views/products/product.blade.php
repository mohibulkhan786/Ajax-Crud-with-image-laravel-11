<!--extend layout blade file  -->
@extends('products.layout')

<!--add section-product-blade-file  -->
@section('content')

<!-- start div card with margin-top -->
    <div class="card mt-5">
        <h6 class="card-header"><i class="fa-regular fa-credit-card"></i> Laravel 11 Ajax CRUD Example - AMK Development
        </h6>
        <div class="card-body">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                <a class="btn btn-success btn-sm" href="{{ route('home') }}" style="float: left;"> <i class="fa fa-plus"></i> Back</a>
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

