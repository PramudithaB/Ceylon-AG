<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanySetting::firstOrCreate(
            ['id' => 1],
            [
                'company_name' => 'Ceylon Agro Marketing (Pvt) Ltd.',
                'address' => 'No. 123, Agribusiness Zone, Colombo, Sri Lanka',
                'phone' => '+94 11 234 5678',
                'email' => 'info@ceylonag.com',
                'website' => 'www.ceylonag.com',
                'logo_path' => 'images/logo.png',
                'bank_name' => 'Bank of Ceylon',
                'branch' => 'Corporate Branch, Colombo',
                'account_name' => 'Ceylon Agro Marketing (Pvt) Ltd',
                'account_number' => '000847362910',
                'swift_code' => 'BCEYLKLX',
                'default_terms' => "1. Quotation is valid for 30 days from issue date.\n2. Payment terms: 50% advance upon confirmation, balance on delivery.\n3. Prices are inclusive of standard handling charges unless specified.",
                'default_delivery_period' => '3-5 business days upon order confirmation',
            ]
        );
    }
}
