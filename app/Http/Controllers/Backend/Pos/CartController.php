<?php

namespace App\Http\Controllers\Backend\Pos;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\PosCart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $cartItems = PosCart::where('user_id', auth()->id())
                ->where('branch_id', auth()->user()->branch_id)
                ->with('product')
                ->latest('created_at')
                ->get()
                ->map(function ($item) {
                    // Calculate row total for each item
                    $item->row_total = round(($item->quantity * $item->price),2);
                    return $item;
                });
            $total = $cartItems->sum('row_total');
            return response()->json([
                'carts' => $cartItems,
                'total' => round($total, 2)
            ]);
        }
        // clear cart
        PosCart::where('user_id', auth()->id())
            ->where('branch_id', auth()->user()->branch_id)
            ->delete();
        return view('backend.cart.index');
    }
    public function getProducts(Request $request)
    {
        $branchId = auth()->user()->branch_id;
        $productsQuery = Product::query();

        // If barcode is provided, try to find and add directly if it's a specific request
        if ($request->filled('barcode')) {
            $barcode = trim($request->barcode);
            $product = Product::where('sku', $barcode)->first();
            
            if ($product) {
                // Return this single product
                if ($request->wantsJson()) {
                    return new ProductResource($product);
                }
            }
            return response()->json(['message' => 'Product not found', 'sku' => $barcode], 404);
        }

        // Standard search
        $productsQuery->when($request->search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
        });

        $products = $productsQuery->latest()->paginate(96);
        if ($request->wantsJson()) {
            return ProductResource::collection($products);
        }
    }

    public function quickCreateProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'purchase_price' => $request->price * 0.8, // Default 20% margin
            'branch_id' => auth()->user()->branch_id,
            'status' => 1,
            'quantity' => 100, // Default stock for quick add
        ]);

        return response()->json([
            'message' => 'Product created and added to cart',
            'product' => new ProductResource($product)
        ]);
    }

    public function store(Request $request)
    {
        // Validate request input
        $request->validate([
            'id' => 'required|exists:products,id',
            'price' => 'nullable|numeric|min:0',
        ]);

        $product_id = $request->id;

        // Fetch the product
        $product = Product::find($product_id);

        // Check if the product is active
        if (!$product->status) {
            return response()->json(['message' => 'Product is not available'], 400);
        }

        // Fetch the cart item for the current user and product
        $cartItem = PosCart::where('user_id', auth()->id())
            ->where('branch_id', auth()->user()->branch_id)
            ->where('product_id', $product_id)
            ->first();

        if ($cartItem) {
            // If the product is already in the cart, increment the quantity
            $cartItem->quantity += 1;
            $cartItem->save();
            return response()->json(['message' => 'Quantity updated', 'quantity' => $cartItem->quantity], 200);
        } else {
            // If not in the cart, create a new cart item
            $cart = new PosCart();
            $cart->user_id = auth()->id();
            $cart->branch_id = auth()->user()->branch_id;
            $cart->product_id = $product_id;
            $cart->quantity = 1;
            // Set the price from the request, fallback to product price, else 0
            $cart->price = ($request->price !== null)
                ? $request->price
                : (($product->price ?? 0));
            $cart->save();
            return response()->json(['message' => 'Product added to cart', 'quantity' => 1], 201);
        }
    }

    public function increment(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:pos_carts,id'
        ]);

        $cart = PosCart::with('product')->findOrFail($request->id);
        $cart->quantity = $cart->quantity + 1;
        $cart->save();
        return response()->json(['message' => 'Cart Updated successfully'], 200);
    }
    public function decrement(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:pos_carts,id'
        ]);
        $cart = PosCart::findOrFail($request->id);
        if ($cart->quantity <= 1) {
            return response()->json(['message' => 'Quantity cannot be less than 1.'], 400);
        }
        $cart->quantity = $cart->quantity - 1;
        $cart->save();
        return response()->json(['message' => 'Cart Updated successfully'], 200);
    }
    public function updatePrice(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:pos_carts,id',
            'price' => 'required|numeric|min:0',
        ]);

        $cart = PosCart::findOrFail($request->id);
        $cart->price = $request->price;
        $cart->save();

        return response()->json(['message' => 'Price updated successfully'], 200);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:pos_carts,id'
        ]);

        $cart = PosCart::findOrFail($request->id);
        $cart->delete();

        return response()->json(['message' => 'Item successfully deleted'], 200);
    }


    public function empty()
    {
        $deletedCount = PosCart::where('user_id', auth()->id())
            ->where('branch_id', auth()->user()->branch_id)
            ->delete();

        if ($deletedCount > 0) {
            return response()->json(['message' => 'Cart successfully cleared'], 200);
        }

        return response()->json(['message' => 'Cart is already empty'], 204);
    }
}
