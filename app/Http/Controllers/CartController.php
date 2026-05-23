<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Product;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index() {

        $customer = Auth::user()->customer;

        $carts = CartItem::with('product')
                ->where('customer_id', $customer->id)
                ->latest()
                ->get();

        return Inertia::render('Cart/Index', [
            'carts' => $carts,  
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $customer = Auth::user()->customer;
        $product = Product::findOrFail($request->product_id);

        if ($request->qty > $product->stok) {

            return back()->with('error', 'Stock tidak cukup');
        }

        $cartItem = CartItem::where('customer_id', $customer->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {

            $newQty = $cartItem->qty + $request->qty;
            // validasi stok
            if ($newQty > $product->stok) {

                return back()->with('error', 'Stock tidak cukup');
            }

            $cartItem->update([
                'qty' => $newQty,
            ]);
        } else {
            CartItem::create([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'qty' => $request->qty
            ]);
        }
        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request, CartItem $cartItem) {
        $customer = Auth::user()->customer;
        if ($cartItem->customer_id !== $customer->id) {
            abort(403);
        }

        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        if ($request->qty > $cartItem->product->stok) {
            return back()->with('error', 'Stock tidak cukup');
        }

        $cartItem->update([
            'qty' => $request->qty,
        ]);

        return back()->with('success', 'Keranjang berhasil diperbarui');
    }

    public function destroy(CartItem $cartItem) {
        $customer = Auth::user()->customer;
        if ($cartItem->customer_id !== $customer->id) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }
}
