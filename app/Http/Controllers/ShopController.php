<?php

namespace App\Http\Controllers;

use Export;
use Importer;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Content;
use Illuminate\Support\Facades\Auth;
class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Auth::check()) {
            $state = 'LOGOUT';
            $email = Auth::user()->email;
        }else{
            $state = 'LOGIN';
            $email = '';
        }

        $pagination = 9;
        $categories = Category::all();
        $data =  Content::latest()->first();
        if(request()->category){
            $products = Product::with('categories')->whereHas('categories', function($query){
                $query->where('slug', request()->category);
            });
            $categoryName = optional($categories->where('slug', request()->category)->first())->name;
        }else{
        $products = Product::where('featured', true);
        $categoryName = 'Featured';
        }
        if(request()->sort == 'low_high'){
            $products = $products->orderBy('price')->paginate(9);
        }elseif (request()->sort == 'high_low') {
            $products = $products->orderBy('price', 'desc')->paginate(9);
        }else{
            $products = $products->paginate(9);
        }
        $page = $products->appends(request()->input())->links();
        $low_high = route('shop.index', ['category' => request()->category , 'sort' => 'low_high']) ;
        $high_low = route('shop.index', ['category' => request()->category , 'sort' => 'high_low']) ;


        return view('shop')->with([
            'type' => $data->banner_type,
            'products' => $products,
            'categories' => $categories,
            'categoryName' => $categoryName,
            'page' => $page,
            'information' => $data->banner_message,
            'data' => request()->category,
            'sort' => $low_high,
            'sort2' => $high_low,
            'state' => $state,
            'email' => $email
        ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $data =  Content::latest()->first();
        $query = $request->input('query');

        $products = Product::where('name', 'like', "%$query%")->get();
        return view('search-result')->with(['
            products' => $products,
            'type' => $data->banner_type,
            'products' => $products,
            'information' => $data->banner_message,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (Auth::check()) {
            $state = 'LOGOUT';
            $email = Auth::user()->email;
        }else{
            $state = 'LOGIN';
            $email = '';
        }
        $data =  Content::latest()->first();
        $product = Product::where('slug', $slug)->firstOrFail();
        $products = Product::inRandomOrder()->take(3)->get();
        $mightAlsoLike = Product::where('slug', '!=' , $slug)->mightAlsoLike()->get();
        if($product->quantity > setting('site.stock_threshold')){
            $stockLevel = '<span class="badge badge-success" style="font-size:15px;">In Stock</span>';
        }elseif($product->quantity <= setting('site.stock_threshold') && $product->quantity > 0){
              $stockLevel = '<span class="text-danger badge badge-warning" style="font-size:15px;">Low Stock</span>';
        }else{
            $stockLevel = '<span class="text-white badge badge-danger" style="font-size:15px; color:white;">Not Available</span>';
        }

   //     $files =  json_decode($product->specifications, true);
     //   $excel =  $files[0]['download_link'];



        return view('product')->with([
            'product' => $product,
            'products' => $products,
            'type' => $data->banner_type,
            'information' => $data->banner_message,
            'images' => json_decode($product->images, true),
            'image' => $product->image,
            'mightAlsoLike' => $mightAlsoLike,
            'state' => $state,
            'stockLevel' => $stockLevel,
            'email' => $email
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
