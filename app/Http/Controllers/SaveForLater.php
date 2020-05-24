<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;


class SaveForLater extends Controller
{
   public function destroy($id){
        Cart::instance('saveForLater')->remove($id);
        return back()->with('success_message', 'Item as been removed');
   }
   public function switchToCart($id){
        $item = Cart::instance('saveForLater')->get($id);
        Cart::instance('saveForLater')->remove($id);
        $duplicates = Cart::instance('default')->search(function($cartItem, $rowId) use ($id){
            return $rowId === $id;
        });
        if($duplicates->isNotEmpty()){
            return redirect()->route('cart.index')->with('success_message', 'Item already in cart');
        }
        Cart::instance('default')->add($item->id, $item->name, 1, $item->price, 0, ['slug' => $item->slug ])
        ->associate('App\Product');
        return redirect()->route('cart.index')->with('success_message', 'Item has been moved  to  cart ');
   }
}
