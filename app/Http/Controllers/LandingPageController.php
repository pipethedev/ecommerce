<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User;
use App\Content;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;

class LandingPageController extends Controller
{
    public function index(){
        if (Auth::check()) {
            $state = 'LOGOUT';
        }else{
            $state = 'LOGIN';
        }
        $data =  Content::latest()->first();
        $images =  $data->banner_images;
        $products = Product::where('featured', true)->take(8)->inRandomOrder()->get();
        $date = Carbon::today()->subDays(30);
        $latests = Product::where('created_at', '>=', $date)->inRandomOrder()->take(8)->get();
        return view('index')->with([
            'products' => $products,
            'state' => $state,
            'datas' => $data,
            'promoImage' => $data->promo_image,
            'type' => $data->banner_type,
            'information' => $data->banner_message,
            'latests' => $latests, 
            'value' => request()->input('query'),
            'images' => json_decode($data->banner_images, true),
        ]);
}

    public function fetch(Request $request){
        if($request->get('query')){
            $query = $request->get('query');
            $data = Product::where('name', 'like', '%' . $query . '%')->orWhere('description', 'like', '%' . $query . '%')->take(12)->get();
            $output = '<div class="row">';
            foreach($data as $row){
            $output .='
            <a href="/shop/'.$row->slug.'" style="color:black;">
                    <div class="col">
                    <img src="http://127.0.0.1:8000/storage/'.$row->image.'" height="80">
                        <p style="font-weight:bold;">'.$row->name.'</p>
                        <span>NGN: '.$row->price.'</span>
                    </div>
                    </a>';              
             }
             $output .= '</div>';
             echo $output;
        }
    }


}