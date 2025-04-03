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
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="View" class="me-1 btn btn-info btn-sm showProduct"><i class="fa-regular fa-eye"></i> </a>';

                           $btn = $btn. '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct"><i class="fa-regular fa-pen-to-square"></i> </a>';
   
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct"><i class="fa-solid fa-trash"></i></a>';
    
                            return $btn;
                    })
                    ->rawColumns(['image','action'])
                    ->make(true);
        }
        
        return view('products/product');
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
