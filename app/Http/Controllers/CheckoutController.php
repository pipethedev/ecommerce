<?php

// require_once __DIR__ .'/../Paystack.php';

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Coupon;
use App\User;
use App\Content;
use DB;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;


class CheckoutController extends Controller
{
    /*
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
        $data =  Content::latest()->first();
        $tax = config('cart.tax') / 100;
        $discount = session()->get('coupon')['discount'] ?? 0;       

        $count = Cart::count();
        $content = Cart::content();
        $tone = Cart::total();
        $total = str_replace(',', '', $tone);
        $subtotal = Cart::subtotal();
        $sub  =  str_replace(',', '', $subtotal);
        $newSubtotal =  $sub - $discount;
        $newTax = $newSubtotal * $tax;
        $tone1 = $newSubtotal * ( 1 + $tax);
        $newTotal = str_replace(',', '', $tone1);
        $session = session()->has('coupon');
        $sessionName = session()->get('coupon')['discount'];
        $metadata = Cart::content()->map(function($item){
                return $item->model->slug.','.$item->qty.','.$item->price;
            })->values()->toJson();


        return $data->type;
        // return view('checkout')->with([
        //     'datas' => $data,
        //     'type' => $data->banner_type,
        //     'information' => $data->banner_message,
        //     'counts' => $count,
        //     'contents' => $content,
        //     'total' => $total,
        //     'metadata' => $metadata,
        //     'subtotal' => $subtotal,
        //     'session' => $session,
        //     'sessionName' => $sessionName,
        //     'newSubtotal' => $newSubtotal,
        //     'newTotal' => $newTotal,
        //     'newTax' => $newTax,
        //     'email' => $email,
        //     'state' => $state,
        //     'allcontents' => $metadata
        // ]);
    }

    private function getNumbers(){
        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $contents = Cart::content()->map(function($item){
                return $item->model->slug.','.$item->qty.','.$item->price;
            })->values()->toJson();
            return $contents;



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
