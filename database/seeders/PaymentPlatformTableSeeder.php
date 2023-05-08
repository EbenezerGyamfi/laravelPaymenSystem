<?php

namespace Database\Seeders;

use App\Models\PaymentPlatform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentPlatformTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentPlatform::create([
            'name' => 'stripe',
            'image' => 'img/payment-platforms/stripe.jpg'
        ]);

        PaymentPlatform::create([
            'name' => 'paypal',
            'image' => 'img/payment-platforms/paypal.jpg'
        ]);
        //


    }
}
