<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\CustomerProfile;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Vendor Utama (Untuk tes)
        $mainVendor = User::create([
            'name' => 'Juragan Laptop',
            'email' => 'vendor@example.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
        ]);
        if(!$mainVendor->vendorProfile) {
            VendorProfile::create(['user_id' => $mainVendor->id, 'shop_name' => 'Juragan Laptop Official', 'verification_status' => 'approved']);
        } else {
            $mainVendor->vendorProfile->update(['shop_name' => 'Juragan Laptop Official', 'verification_status' => 'approved']);
        }

        // Customer Utama (Untuk tes)
        $custUtama = User::create([
            'name' => 'Budi Pembeli',
            'email' => 'cust@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
        if(!$custUtama->customerProfile) {
             CustomerProfile::create(['user_id' => $custUtama->id]);
        }

        // data masif
        echo "Seeding 10 Vendors...\n";
        $vendors = User::factory(10)->create(['role' => 'vendor']);

        foreach($vendors as $v) {
            $v->refresh();

            // Cek apakah profile benar-benar ada
            if ($v->vendorProfile) {
                $v->vendorProfile->update([
                    'shop_name' => $v->name . ' Store',
                    'verification_status' => 'approved'
                ]);
            } else {
                // Jaga-jaga jika observer gagal/telat, kita buat manual
                \App\Models\VendorProfile::create([
                    'user_id' => $v->id,
                    'shop_name' => $v->name . ' Store',
                    'verification_status' => 'approved'
                ]);
            }
        }


        echo "Seeding 100 Customers...\n";
        $customers = User::factory(100)->create(['role' => 'customer']);


        echo "Seeding Categories...\n";
        $parentCategories = Category::factory(5)->create();
        // Buat 5 Sub-kategori untuk setiap induk (Total ~10 lebih)
        foreach ($parentCategories as $parent) {
            Category::factory(2)->create(['parent_id' => $parent->id]);
        }
        $allCategories = Category::all();


        echo "Seeding 500 Products...\n";
        $allVendors = $vendors->push($mainVendor);
        $products = Product::factory(500)->make()->each(function ($product) use ($allVendors, $allCategories) {
            $product->vendor_id = $allVendors->random()->id;
            $product->category_id = $allCategories->random()->id;
            $product->save();
        });


        User::where('role', 'customer')->chunk(20, function ($customers) use ($products) {

            foreach ($customers as $customer) {
                $jumlahOrder = rand(1, 3);

                for ($i = 0; $i < $jumlahOrder; $i++) {
                    $randomProducts = $products->random(rand(1, 5));
                    $totalPrice = $randomProducts->sum('price');

                    $order = Order::create([
                        'user_id' => $customer->id,
                        'total_price' => $totalPrice,
                        'status' => fake()->randomElement(['Pending', 'Paid', 'Shipped', 'Completed', 'Cancelled']),
                        'shipping_name' => $customer->name,
                        'shipping_phone' => '08123456789',
                        'shipping_address' => 'Jl. Dummy Data dengan Chunk',
                    ]);

                    foreach($randomProducts as $p) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $p->id,
                            'quantity' => 1,
                            'price' => $p->price,
                        ]);
                    }
                }
            }

            echo ".";
        });

        echo "SEEDING SELESAI! Data masif berhasil dibuat.\n";
    }
}
