<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'email',
        'website',
        'logo_path',
        'bank_name',
        'branch',
        'account_name',
        'account_number',
        'swift_code',
        'default_terms',
        'default_delivery_period',
    ];

    /**
     * Get or create company settings singleton record.
     */
    public static function getSettings(): self
    {
        $settings = self::first();
        if ($settings) {
            return $settings;
        }

        return self::create([
            'company_name' => 'Ceylon AG',
            'address' => 'I Jothipala Mawatha, Malabe',
            'phone' => '076 538 0483',
            'email' => 'info@ceylonagromarketing.lk',
            'website' => 'https://ceylonagromarketing.lk/',
            'logo_path' => 'images/logo.png',
            'bank_name' => 'Bank of Ceylon',
            'branch' => 'Corporate Branch, Colombo',
            'account_name' => 'Ceylon Agro Marketing (Pvt) Ltd',
            'account_number' => '000847362910',
            'swift_code' => 'BCEYLKLX',
            'default_terms' => "1. Quotation is valid for 30 days from issue date.\n2. Payment terms: 50% advance upon confirmation, balance on delivery.",
            'default_delivery_period' => '3-5 business days upon order confirmation',
        ]);
    }
}
