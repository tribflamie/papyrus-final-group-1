<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\Reply;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class visitorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::all();
        $products = Product::all();
        $feedbacks = Feedback::all();
        return view('home', compact('products', 'categories', 'feedbacks'));
    }

    //show product information and user-comments on product-detail
    public function productDetail($id)
    {
        $product = Product::find($id);
        $replies = Reply::all();
        $feedbacks = Feedback::all();
        $lastest = Feedback::orderBy('created_at', 'DESC')->get();
        $stars5 = Feedback::where('rating', 5)->get();
        $stars4 = Feedback::where('rating', 4)->get();
        $stars3 = Feedback::where('rating', 3)->get();
        $stars2 = Feedback::where('rating', 2)->get();
        $stars1 = Feedback::where('rating', 1)->get();
        return view('product-detail', compact('product', 'feedbacks', 'replies', 'lastest', 'stars5', 'stars4', 'stars3', 'stars2', 'stars1'));
    }
    //show user-comments history

    //user-feedbacks
    public function feedbacks()
    {
        $user = Auth::user()->id;
        $feedbacks = Product::join('feedbacks', 'products.id', '=', 'feedbacks.products_id')
            ->where('users_id', $user)
            ->get();
        return view('feedbacks', compact('feedbacks', 'user', 'feedbacks'));
    }
    //cart add, update and remove
    public function cart()
    {
        return view('cart');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart($id)
    {
        $product = Product::find($id);
        if (!$product) {
            abort(404);
        }
        $cart = session()->get('cart');
        // if cart is empty then this the first product
        if ($cart == null) {
            session()->put('cart', []);
            $cart = [
                $id => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    "discount" => $product->discount,
                    "author" => $product->author,
                    "photo" => $product->photo
                ]
            ];
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added to cart successfully!');
        }
        // if cart not empty then check if this product exist then increment quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added to cart successfully!');
        }
        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "id" => $product->id,
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->price,
            "discount" => $product->discount,
            "author" => $product->author,
            "photo" => $product->photo
        ];
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
    }
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }
    public function orderControl(Request $request)
    {
        //chưa lấy userID được
        //$user=session()->get('user');
        $cart = session()->get('cart');
        if ($cart) :
            $count = 0;
            $total = 0;
            $discount = session()->get('discount');
            foreach ($cart as $id => $details) :
                $count++;
                $total += $details['price'] * $details['quantity'];
            endforeach;
            $total *= (1 - $discount);
            $query = "insert into orders (users_id,total_quantity,total_price,status) values (1,{$count},{$total},'Processing')";
            DB::insert($query);

            unset($details);
            $rs = DB::select('select id from orders where id=(select max(id) from orders)');
            //insert into orderDetails
            foreach ($cart as $id => $details) :
                $query2 = "insert into order_details (orders_id,products_id,unit_quantity,unit_total) values ({$rs[0]->id},{$id},{$details['quantity']},{$details['price']}*{$details['quantity']})";
                DB::insert($query2);
            endforeach;
            session()->put('cart', null);
        endif;
        if ($request->session()->get('couponValue') > 0) :
            $coupon = $request->session()->get('coupon');
            DB::table('coupons')
                ->where('code', $coupon)  // find coupon code
                ->limit(1)  // optional - to ensure only one record is updated.
                ->update(array('status' => 'used', 'orders_id' => $rs[0]->id));  // update the record in the DB. 
        endif;
        return redirect('/home')->with('orderSuccess', 'Order confirmed, Please wait for us to check.');
    }
    public function checkCoupon(Request $request)
    {
        $coupon = $request->input('checkCoupon');
        $query = "select * from coupons where code='{$coupon}'";
        $rs = DB::select($query);
        if (!$rs) :
            return redirect('/cart')->with('msgFail', 'Coupon does not exist!');
        endif;
        if ($rs[0]->status == 'used') :
            return redirect('/cart')->with('msgFail', 'Coupon is already used!');
        endif;
        if (strtotime(date("Y/m/d")) > strtotime($rs[0]->exp_date)) :
            return redirect('/cart')->with('msgFail', 'Coupon expired!');
        endif;
        if ($rs) :
            session()->put('couponValue', $rs[0]->value);
            session()->put('coupon', $rs[0]->code);
            return redirect('/cart')->with('msgSuccess', 'Coupon is avaible!');
        endif;
    }
}
