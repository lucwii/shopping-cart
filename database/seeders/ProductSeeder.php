<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Tastature
            [
                'name' => 'Logitech G Pro X Mechanical Gaming Keyboard',
                'price' => 129.99,
                'stock_quantity' => 15,
                'image' => 'products/logitech-g-pro-x.jpg',
            ],
            [
                'name' => 'Razer BlackWidow V3 RGB Mechanical Keyboard',
                'price' => 139.99,
                'stock_quantity' => 8,
                'image' => 'products/razer-blackwidow.jpg',
            ],
            [
                'name' => 'Corsair K70 RGB MK.2 Mechanical Keyboard',
                'price' => 159.99,
                'stock_quantity' => 12,
                'image' => 'products/corsair-k70.jpg',
            ],
            [
                'name' => 'SteelSeries Apex Pro TKL',
                'price' => 189.99,
                'stock_quantity' => 6,
                'image' => 'products/steelseries-apex.jpg',
            ],
            [
                'name' => 'Keychron K8 Wireless Mechanical Keyboard',
                'price' => 89.99,
                'stock_quantity' => 20,
                'image' => 'products/keychron-k8.jpg',
            ],

            // Miševi
            [
                'name' => 'Logitech G502 HERO Gaming Mouse',
                'price' => 79.99,
                'stock_quantity' => 25,
                'image' => 'products/logitech-g502.jpg',
            ],
            [
                'name' => 'Razer DeathAdder V2 Gaming Mouse',
                'price' => 69.99,
                'stock_quantity' => 18,
                'image' => 'products/razer-deathadder.jpg',
            ],
            [
                'name' => 'SteelSeries Rival 3 Wireless',
                'price' => 59.99,
                'stock_quantity' => 14,
                'image' => 'products/steelseries-rival.jpg',
            ],
            [
                'name' => 'Corsair Dark Core RGB Pro',
                'price' => 89.99,
                'stock_quantity' => 10,
                'image' => 'products/corsair-dark-core.jpg',
            ],
            [
                'name' => 'Logitech MX Master 3S',
                'price' => 99.99,
                'stock_quantity' => 7,
                'image' => 'products/logitech-mx-master.jpg',
            ],

            // Slušalice
            [
                'name' => 'HyperX Cloud II Gaming Headset',
                'price' => 99.99,
                'stock_quantity' => 16,
                'image' => 'products/hyperx-cloud-ii.jpg',
            ],
            [
                'name' => 'SteelSeries Arctis 7 Wireless',
                'price' => 149.99,
                'stock_quantity' => 9,
                'image' => 'products/steelseries-arctis.jpg',
            ],
            [
                'name' => 'Razer BlackShark V2 Pro',
                'price' => 179.99,
                'stock_quantity' => 5,
                'image' => 'products/razer-blackshark.jpg',
            ],

            // Podloge za miš
            [
                'name' => 'SteelSeries QcK Gaming Mouse Pad',
                'price' => 14.99,
                'stock_quantity' => 30,
                'image' => 'products/steelseries-qck.jpg',
            ],
            [
                'name' => 'Razer Goliathus Extended Chroma',
                'price' => 49.99,
                'stock_quantity' => 12,
                'image' => 'products/razer-goliathus.jpg',
            ],

            // Monitori
            [
                'name' => 'ASUS TUF Gaming 27" 144Hz Monitor',
                'price' => 299.99,
                'stock_quantity' => 8,
                'image' => 'products/asus-tuf-monitor.jpg',
            ],
            [
                'name' => 'LG UltraGear 32" 4K Gaming Monitor',
                'price' => 699.99,
                'stock_quantity' => 4,
                'image' => 'products/lg-ultragear.jpg',
            ],

            // Webcam
            [
                'name' => 'Logitech C920 HD Pro Webcam',
                'price' => 79.99,
                'stock_quantity' => 11,
                'image' => 'products/logitech-c920.jpg',
            ],

            // Proizvod sa malom količinom za testiranje low stock notifikacije
            [
                'name' => 'Limited Edition RGB Mousepad',
                'price' => 34.99,
                'stock_quantity' => 2,
                'image' => 'products/limited-mousepad.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
