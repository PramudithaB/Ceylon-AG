<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = User::role('Client')->first();

        if ($client) {
            Payment::create([
                'payment_number' => Payment::generatePaymentNumber(),
                'client_id' => $client->id,
                'amount' => 75000.00,
                'payment_date' => now()->subDays(2),
                'bank_name' => 'Commercial Bank of Ceylon',
                'reference_number' => 'CBC-DEP-8839102',
                'payment_screenshot' => 'payments/sample_slip.png',
                'remarks' => 'Bank counter deposit for Product Assignment allocation.',
                'status' => Payment::STATUS_PENDING,
            ]);
        }
    }
}
