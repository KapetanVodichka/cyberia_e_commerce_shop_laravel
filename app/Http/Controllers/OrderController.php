<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller {
    public function index() {
        if (auth()->user()->isAdmin()) {
            $orders = Order::with('user', 'orderItems.product')->paginate(10);
        } else {
            $orders = auth()->user()->orders()->with('orderItems.product')->paginate(10);
        }
        return OrderResource::collection($orders);
    }

    public function store(Request $request) {
        if (auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Администраторы не могут создавать заказы'], 403);
        }

        $data = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = 0;
        $orderItems = [];

        DB::transaction(function () use ($data, &$totalAmount, &$orderItems) {
            foreach ($data['products'] as $item) {
                $product = Product::find($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Недостаточно товара на складе для продукта {$product->name}");
                }
                $totalAmount += $product->price * $item['quantity'];
                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
                $product->decrement('stock', $item['quantity']);
            }

            $user = auth()->user();
            if ($user->balance < $totalAmount) {
                throw new \Exception('Недостаточно средств на балансе');
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'new',
            ]);

            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            $user->decrement('balance', $totalAmount);

            Log::info("Заказ #{$order->id} создан пользователем {$user->name}");
        });

        return response()->json(['message' => 'Заказ создан'], 201);
    }

    public function update(Request $request, $id) {
        $order = Order::findOrFail($id);
        $data = $request->validate([
            'status' => 'required|in:confirmed,canceled',
        ]);

        if ($order->status === $data['status']) {
            return response()->json(['message' => 'Статус не изменен'], 400);
        }

        if ($data['status'] === 'canceled' && $order->status === 'new') {
            DB::transaction(function () use ($order) {
                $user = $order->user;
                $user->increment('balance', $order->total_amount);
                foreach ($order->orderItems as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
                $order->update(['status' => 'canceled']);
            });
        } else {
            $order->update(['status' => $data['status']]);
        }

        Log::info("Статус заказа #{$order->id} изменен на {$order->status} администратором " . auth()->user()->name);
        return new OrderResource($order);
    }
}
