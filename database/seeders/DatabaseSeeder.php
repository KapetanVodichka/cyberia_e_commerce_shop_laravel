<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'name' => 'Admin User',
            'role' => 'admin',
            'password' => bcrypt('password'),
            'balance' => 0,
        ]);

        User::factory()->create([
            'email' => 'customer@example.com',
            'name' => 'Customer User',
            'role' => 'customer',
            'password' => bcrypt('password'),
            'balance' => 999,
        ]);

        User::factory(10)->create();

        Category::factory(10)->create()->each(function ($category) {
            Product::factory(10)->create(['category_id' => $category->id]);
        });

        Order::factory(20)->create()->each(function ($order) {
            $items = OrderItem::factory(2)->create(['order_id' => $order->id]);
            $order->update(['total_amount' => $items->sum(fn($item) => $item->price * $item->quantity)]);
        });
    }
}
