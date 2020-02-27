<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //// Permission from Spatie to Product-Managment
    function __construct()
    {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
        $this->middleware('permission:product-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    //Show all Users
    public function index()
    {
        $products = Product::latest()->paginate(5);
        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    //Displaying The form to create new Product
    public function create()
    {
        return view('products.create');
    }

    //Validate if the Form meet all requirements
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success','Product created successfully.');
    }

    //show a certain Product By Id
    public function show(Product $product)
    {
        return view('products.show',compact('product'));
    }

    //Show the form for editing the specified Product
    public function edit(Product $product)
    {
        return view('products.edit',compact('product'));
    }

    //Update Product
    public function update(Request $request, Product $product)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success','Product updated successfully');
    }

    //Delete a certain Product
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success','Product deleted successfully');
    }
}
