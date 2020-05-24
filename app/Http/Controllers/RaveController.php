<?php

namespace App\Http\Controllers;
use Mail;
use Rave;
use App\User;
use App\Order;
use App\Product;
use App\Coupon;
use App\Content;
use App\Mail\OrderPlaced;
use App\OrderProduct;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

class RaveController extends Controller
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

        $tax = config('cart.tax') / 100;
        $discount = session()->get('coupon')['discount'] ?? 0;       
        $data =  Content::latest()->first();
        $count = Cart::count();
        $content = Cart::content();
        $tone = Cart::total();
        $total = str_replace(',', '', $tone);
        $stotal = Cart::subtotal();
        $subtotal =  str_replace(',', '', $stotal);
        $sub  =  str_replace(',', '', $subtotal);
        $snewSubtotal =  $sub - $discount;
        $newSubtotal = str_replace(',', '', $snewSubtotal);
        $newTax = $newSubtotal * $tax;
        $tone1 = $newSubtotal * ( 1 + $tax);
        $newTotal = str_replace(',', '', $tone1);
        $session = session()->has('coupon');
        $sessionName = session()->get('coupon')['discount'];
        $metadata = Cart::content()->map(function($item){
                return $item->model->slug.','.$item->qty.','.$item->price;
            })->values()->toJson();


        return view('checkout')->with([
            'datas' => $data,
            'information' => $data->banner_message,
            'type' => $data->banner_type,
            'counts' => $count,
            'contents' => $content,
            'total' => $total,
            'metadata' => $metadata,
            'subtotal' => $subtotal,
            'session' => $session,
            'sessionName' => $sessionName,
            'newSubtotal' => $newSubtotal,
            'newTotal' => $newTotal,
            'newTax' => $newTax,
            'email' => $email,
            'state' => $state,
            'allcontents' => $metadata
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
            $contents = Cart::content()->map(function($item){
                return $item->model->slug.','.$item->qty.','.$item->price;
            })->values()->toJson();
            dd($contents);
    }
  /**
   * Initialize Rave payment process
   * @return void
   */
  public function initialize(Request $request)
  {
    if($this->productsAreNoLongerAvailable()){
      return back()->withErrors('Sorry one of your items in your cart is no longer available');
    }
    Rave::initialize(route('callback'));
    $request->session()->put('data', $request->all());
  }

  public function offline(Request $request){
    if($this->productsAreNoLongerAvailable()){
      return back()->withErrors('Sorry one of your items in your cart is no longer available');
    }
    $order = $this->offlinePayment($request, null);
    $this->decreaseQuantities($request, null);
    Mail::send(new OrderPlaced($order));
    Cart::destroy();
    return redirect()->route('thankyou.index')->with('success_message', 'Your order has been sent successfully');
  }

  /**
   * Obtain Rave callback information
   * @return void
   */
  public function callback(Request $request)
  {
    $resp = request()->resp;


      if ($resp){
      //  dd($data);
      $order = $this->addToOrdersTables($request, null);
      Mail::send(new OrderPlaced($order));
      //Decrease Quantity
      $this->decreaseQuantities($request, null);
      Cart::destroy();
      return redirect()->route('thankyou.index')->with('success_message', 'Your payment was successfull');
      }
  }

  protected function addToOrdersTables($request, $error){
          $data =  $request->session()->get('data');
          $discount = session()->get('coupon')['discount'] ?? 0;
          $discount_code = session()->get('coupon')['name'] ?? 0; 
          $order = Order::create([
          'user_id' => auth()->user() ? auth()->user()->id : null,
          'billing_email' => $data['email'],
          'billing_name' => $data['firstname'],
          'billing_address' => $data['address'],
          'billing_city' => $data['city'],
          'billing_province' => $data['province'],
          'billing_postalcode' => $data['zip'],
          'billing_phone' => $data['phonenumber'],
          'billing_name_on_card' => $data['firstname'],
          'billing_discount' => $discount,
          'billing_discount_code' => $discount_code,
          'billing_subtotal' => $data['subtotal'],
          'billing_tax' => $data['tax'],
          'billing_total' => $data['amount'],
          'error' => null
        ]);

        foreach(Cart::content() as $item){
          OrderProduct::create([
            'order_id' => $order->id,
            'product_id' => $item->model->id,
            'quantity' => $item->qty,
          ]);
        }
        return $order;
  }

  protected function decreaseQuantities($request, $error){
    $content = Cart::content();
    foreach($content as $item){
      $ok = Product::where('slug', $item->model->slug)->get();
      $id =  $ok[0]['id'];
      $product = Product::find($id);
      $product->update(['quantity' => $product->quantity - $item->qty ]);
    }
  }

  protected function offlinePayment($request, $error){
          $discount = session()->get('coupon')['discount'] ?? 0;
          $discount_code = session()->get('coupon')['name'] ?? 0; 
          $order = Order::create([
          'user_id' => auth()->user() ? auth()->user()->id : null,
          'billing_email' => $request->email,
          'billing_name' => $request->firstname,
          'billing_address' => $request->address,
          'billing_city' => $request->city,
          'billing_province' => $request->province,
          'billing_postalcode' => $request->zip,
          'billing_phone' => $request->phonenumber,
          'billing_name_on_card' => $request->firstname,
          'billing_discount' => $discount,
          'billing_discount_code' => $discount_code,
          'billing_subtotal' => $request->subtotal,
          'billing_tax' => $request->tax,
          'billing_total' => $request->amount,
          'billing_gateway' => 'cash',
          'error' => null
        ]);

        foreach(Cart::content() as $item){
          OrderProduct::create([
            'order_id' => $order->id,
            'product_id' => $item->model->id,
            'quantity' => $item->qty,
          ]);
        }
        return $order;
  }

  protected function productsAreNoLongerAvailable(){
        $content = Cart::content();
        foreach($content as $item){
              $ok = Product::where('slug', $item->model->slug)->get();
              $id =  $ok[0]['id'];
              $product = Product::find($id);
              if($product->quantity < $item->qty){
                return true;
              }
        }
        return false;
  }


}



//
