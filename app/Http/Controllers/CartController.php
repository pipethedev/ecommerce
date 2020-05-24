<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;
use App\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Validator;

class CartController extends Controller
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
        }else{
            $state = 'LOGIN';
        }
        $mightAlsoLike = Product::mightAlsoLike()->get();
        $discount = session()->get('coupon')['discount'] ?? 0;    
        $count = Cart::count();
        $content = Cart::content();
        $session = session()->has('coupon');
        $name = session()->has('coupon')['name'];
        $subtotal = Cart::subtotal();
        $tax = config('cart.tax') / 100;
        $total = Cart::total();
        $sub  =  str_replace(',', '', $subtotal);

        $snewSubtotal =  $sub - $discount;
        $newSubtotal = str_replace(',', '', $snewSubtotal);

        $tone1 = $newSubtotal * ( 1 + $tax);
        $newTotal = str_replace(',', '', $tone1);

        $newTax = $newSubtotal * $tax;

        $saved  = Cart::instance('saveForLater')->count();
        $savedItems = Cart::instance('saveForLater')->content();
        $data =  Content::latest()->first();

      // $product = Product::where('slug', 'new-product')->get();

      // $id =  $product[0]['id'];

      // return $id;

        return view('cart')->with([
            'mightAlsoLike' =>  $mightAlsoLike,
            'count' => $count,
            'contents' => $content,
            'subtotal' => $subtotal,
            'tax' => $newTax,
            'session' => $session,
            'total' => $total,
            'name' => $name,
            'saved' => $saved,
            'state' => $state,
            'type' => $data->banner_type,
            'information' => $data->banner_message,
            'savedItems' => $savedItems,
            'newSubtotal' => $newSubtotal,
            'newTotal' => $newTotal
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $duplicates = Cart::search(function($cartItem, $rowId) use ($request){
            return $cartItem->id === $request->id;

        });
        if($duplicates->isNotEmpty()){
            return redirect()->route('cart.index')->with('success_message', 'Item already in cart');
        }
        Cart::add($request->id, $request->name, 1, $request->price, 0, ['slug' => $request->slug])->associate('App\Product');
        return redirect()->route('cart.index')->with('success_message', 'Item was added to your cart');
    }


    public function empty(){
        Cart::destroy();
        //Cart::instance('saveForLater')->destroy();
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
        $validator = Validator::make($request->all(),[
            'quantity' => 'required|numeric|between:1,5'
        ]);

        if($validator->fails()){
            session()->flash('errors', collect(['Quantity must be between 1 and 5']));
            return response()->json(['success' => false ], 400);
        }

    
          $dd = Product::select('quantity')->where('slug', $request->productSlug)->get();
          $quantity =  $dd[0]['quantity'];

        if($request->quantity > $quantity ){
            session()->flash('errors', collect(['We currently do not have enough items in stock.']));
            return response()->json(['success' => false ], 400);           
        }

       
        Cart::update($id, $request->quantity);
        session()->flash('success_message', 'Quantity was updated successfully');
        return response()->json(['success' => true ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switchToSaveForLater($id)
    {
        $item = Cart::get($id);
        Cart::remove($id);
        $duplicates = Cart::instance('saveForLater')->search(function($cartItem, $rowId) use ($id){
            return $rowId === $id;
        });
        if($duplicates->isNotEmpty()){
            return redirect()->route('cart.index')->with('success_message', 'Item already in cart');
        }
        Cart::instance('saveForLater')->add($item->id, $item->name, 1, $item->price, 0, ['slug' => $item->slug ])
        ->associate('App\Product');
        return redirect()->route('cart.index')->with('success_message', 'Item has been saved for later ');
    }

    public function destroy($id){
        Cart::remove($id);
        return back()->with('success_message', 'Item as been removed');
    }
}
