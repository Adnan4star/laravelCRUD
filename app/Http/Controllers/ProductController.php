<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get(); //fetching all details of product
        
        return view('products.index',['products'=>$products]); //use foreach in form to fetch data
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        //validate
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1000'
        ]);

        //Upload image
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('products'), $imageName);
        
        //Insert rest form information
        $product = new Product;
        $product->image = $imageName;
        $product->name = $request->name;
        $product->description = $request->description;

        $product->save();

        //printing success message with sessions 
        return back()->withSuccess('Product Created Successfully!!');
    }

    public function edit($id)
    {
        $product = Product::where('id',$id)->first();
        return view('products.edit',['product'=>$product]);
    }

    public function update(Request $request, $id)
    {
        //validate
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:1000'
        ]);
        $product = Product::where('id',$id)->first();
        if(isset($request->image))
        {
            //Upload image
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('products'), $imageName);
            $product->image = $imageName;
        }

        //Update rest form information
        $product->name = $request->name;
        $product->description = $request->description;

        $product->save();

        //printing success message with sessions 
        return back()->withSuccess('Product Updated!!');
    }
}
