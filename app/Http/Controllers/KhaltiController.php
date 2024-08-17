<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use DB;
use Notification;
use App\User;
use App\Models\Shipping;
use Helper;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;

class KhaltiController extends Controller
{
    public function payment()
    {
        $cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->get()->toArray();
        
        $data = [];
        
        $data['items'] = array_map(function ($item) {
            $name = Product::where('id', $item['product_id'])->pluck('title')->first();
            return [
                'name' => $name,
                'price' => $item['price'],
                'desc' => 'Thank you for using Khalti',
                'qty' => $item['quantity']
            ];
        }, $cart);

        $data['invoice_id'] = 'ORD-' . strtoupper(uniqid());
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = route('payment.success');
        $data['cancel_url'] = route('payment.cancel');

        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $data['total'] = $total;
        if (session('coupon')) {
            $data['shipping_discount'] = session('coupon')['value'];
        }
        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => session()->get('id')]);

        $token = $this->initiateKhaltiPayment($data['total']);

        if ($token) {
            return view('khalti.payment', compact('token', 'data'));
        } else {
            return redirect()->back()->with('error', 'Something went wrong, please try again.');
        }
    }

    private function initiateKhaltiPayment($amount)
    {
        // Implement Khalti payment initiation logic here to get a token
        // This is just a placeholder
        return 'khalti_token';
    }

    public function cancel()
    {
        return redirect()->route('home')->with('error', 'Your payment is canceled.');
    }

    public function success(Request $request)
    {
        $token = $request->input('token');
        $amount = $request->input('amount');

        $args = http_build_query([
            'token' => $token,
            'amount' => $amount
        ]);

        $url = "https://khalti.com/api/v2/payment/verify/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = ['Authorization: test_secret_key_98eed0ae1a134e1e9a5562375294ee4b'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = json_decode($response, true);

        if ($status_code == 200 && isset($response['state']) && $response['state']['name'] == 'Completed') {
            request()->session()->flash('success', 'You successfully paid with Khalti! Thank you.');
            session()->forget('cart');
            session()->forget('coupon');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'Something went wrong, please try again.');
            return redirect()->route('home');
        }
    }

    public function storePayment(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'address1' => 'string|required',
            'address2' => 'string|nullable',
            'coupon' => 'nullable|numeric',
            'phone' => 'numeric|required',
            'post_code' => 'string|nullable',
            'email' => 'string|required',
        ]);
    
        if (empty(Cart::where('user_id', auth()->user()->id)->where('order_id', null)->first())) {
            request()->session()->flash('error', 'Cart is Empty!');
            return back();
        }
    
        $order = new Order();
        $order_data = $request->all();
        $order_data['order_number'] = 'ORD-' . strtoupper(Str::random(10));
        $order_data['user_id'] = $request->user()->id;
        $order_data['shipping_id'] = $request->shipping;
    
        $shipping = Shipping::where('id', $order_data['shipping_id'])->pluck('price');
        $order_data['sub_total'] = Helper::totalCartPrice();
        $order_data['quantity'] = Helper::cartCount();
    
        if (session('coupon')) {
            $order_data['coupon'] = session('coupon')['value'];
        }
    
        if ($request->shipping) {
            $order_data['total_amount'] = Helper::totalCartPrice() + $shipping[0] - (session('coupon')['value'] ?? 0);
        } else {
            $order_data['total_amount'] = Helper::totalCartPrice() - (session('coupon')['value'] ?? 0);
        }
    
        $order_data['status'] = "new";
        $order_data['payment_method'] = "khalti";
    
        $order->fill($order_data);
        $status = $order->save();
    
        if ($order) {
            $users = User::where('role', 'admin')->first();
            $details = [
                'title' => 'New order created',
                'actionURL' => route('order.show', $order->id),
                'fas' => 'fa-file-alt'
            ];
            Notification::send($users, new StatusNotification($details));
    
            session()->forget('cart');
            session()->forget('coupon');
            
            Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);
    
            request()->session()->flash('success', 'Your book successfully placed in order');
            return redirect()->route('home');
        }
    }
    
}