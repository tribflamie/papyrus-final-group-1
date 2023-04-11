<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Feedback;
use App\Models\Reply;
use App\Models\Order;
use App\Models\OrderDetail;

class adminController extends Controller
{
    //return view overview
    public function overview()
    {
        return view('admin.overview');
    }

    //return view user-tables
    public function userTables()
    {
        $users = User::all();
        return view('admin.user-tables', compact('users'));
    }

    //updateRoles
    public function updateRoles($id)
    {
        $data = User::find($id);
        if ($data->role == 'user') {
            $data->role = 'admin';
        } else {
            $data->role = 'user';
        }
        $data->update();
        return redirect()->back()->with('message', 'Role updated successfully');
    }

    //return view product-tables
    public function productTables()
    {
        $total_price = Product::sum('price');
        return view('admin.product-tables', compact('total_price'));
    }

    //create category
    public function saveCategories(Request $request)
    {
        $data = new Category;
        //Store in database
        $data->categories = $request->input('categories');
        $data->description = $request->input('description');
        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/categories/'), $filename);
            $data->photo = $filename;
        }
        $data->save();
        return redirect()->back()->with('message', 'New category created successfully!');
    }

    //update category from id
    public function updateCategoriesId($id)
    {
        $data = Category::find($id);
        return response()->json([
            'categories' => $data
        ]);
    }

    //update category
    public function updateCategories(Request $request)
    {
        $categories_id = $request->input('categories_id');
        $data = Category::find($categories_id);
        $data->categories = $request->input('categories');
        $data->description = $request->input('description');
        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/categories/'), $filename);
            $data->photo = $filename;
        }
        $data->update();
        return redirect()->back()->with('message', 'New category updated successfully!');
    }

    //create product
    public function saveProducts(Request $request)
    {
        $data = new Product;
        //Store in database
        $data->categories_id = $request->input('categories_id');
        $data->name = $request->input('name');
        $data->author = $request->input('author');
        $data->country = $request->input('country');
        $data->published = $request->input('published');
        $data->price = $request->input('price');
        $data->discount = $request->input('discount');
        $data->description = $request->input('description');
        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/shop/'), $filename);
            $data->photo = $filename;
        }
        $data->save();
        return redirect()->back()->with('message', 'New product created successfully!');
    }

    //update product from id
    public function updateProductsId($id)
    {
        $data = Product::find($id);
        return response()->json([
            'products' => $data
        ]);
    }

    //update product
    public function updateProducts(Request $request)
    {
        $product_id = $request->input('product_id');
        $data = Product::find($product_id);
        //Store in database
        $data->categories_id = $request->input('product_categories_id');
        $data->name = $request->input('product_name');
        $data->author = $request->input('product_author');
        $data->country = $request->input('product_country');
        $data->published = $request->input('product_published');
        $data->price = $request->input('product_price');
        $data->discount = $request->input('product_discount');
        $data->description = $request->input('product_description');
        if ($request->hasfile('product_photo')) {
            $file = $request->file('product_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/shop/'), $filename);
            $data->photo = $filename;
        }
        $data->update();
        return redirect()->back()->with('message', 'New product created successfully!');
    }

    //delete product
    public function deleteProducts($id)
    {
        $data = Product::find($id);
        if ($data->status == 'show') {
            $data->status = 'hide';
        } else {
            $data->status = 'show';
        }
        $data->update();
        return redirect()->back()->with('message', 'Product deleted successfully!');
    }

    //return view oder-tables
    public function oderTables()
    {
        $orders = Order::all();
        $orderDetails = OrderDetail::all();
        return view('admin.oder-tables', compact('orders', 'orderDetails'));
    }

    //update Orders
    public function updateOrders($id)
    {
        $data = Order::find($id);
        if ($data->status == 'Pending') {
            $data->status = 'Accepted';
        } else {
            $data->status = 'Pending';
        }
        $data->update();
        return redirect()->back()->with('message', 'Order updated successfully!');
    }

    //return view feedback-tables
    public function feedbackTables()
    {
        $feedbacks = Feedback::all();
        $replies = Reply::all();
        return view('admin.feedback-tables', compact('feedbacks', 'replies'));
    }

    //update Feedbacks
    public function updateFeedbacks($id)
    {
        $data = Feedback::find($id);
        if ($data->status == 'show') {
            $data->status = 'hide';
        } else {
            $data->status = 'show';
        }
        $data->update();
        return redirect()->back()->with('message', 'Feedback updated successfully');
    }

    //update Replies
    public function updateReplies($id)
    {
        $data = Reply::find($id);
        if ($data->status == 'show') {
            $data->status = 'hide';
        } else {
            $data->status = 'show';
        }
        $data->update();
        return redirect()->back()->with('message', 'Feedback updated successfully');
    }
}
