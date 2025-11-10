<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\SupplierCard;
use App\Models\City;
use App\Models\Village;
use App\Models\Currency;
use App\Models\User;

class SupplierCardsSeeder extends Seeder
{
    public function run(): void
    {
        $defaultUser = User::query()->first();
        $defaultCity = City::query()->first();
        $defaultVillage = $defaultCity ? Village::query()->where('city_id', $defaultCity->id)->first() : null;
        $defaultCurrency = Currency::query()->where('code', 'USD')->first();

        foreach (Supplier::all() as $supplier) {
            $card = SupplierCard::firstOrNew(['supplier_id' => $supplier->id]);

            // Basic info
            $card->supplier_id = $supplier->id;
            $card->city_id = $defaultCity?->id;
            $card->village_id = $defaultVillage?->id;
            $card->phone = '0599123456';
            $card->fax = '022345678';
            $card->tax_number = 'TAX-' . str_pad((string)$supplier->id, 5, '0', STR_PAD_LEFT);
            $card->registration_number = 'REG-' . str_pad((string)$supplier->id, 5, '0', STR_PAD_LEFT);
            $card->supplier_type = 'local';
            $card->status = 'active';
            $card->default_currency_id = $defaultCurrency?->id;
            $card->credit_limit = 10000.00;
            $card->bank_name = 'Bank of Palestine';
            $card->bank_account_number = '1234567890';
            $card->iban = 'PS00BOP01234567890';
            $card->beneficiary_name = $supplier->name; // uses translated accessor if any
            $card->bank_account_currency_id = $defaultCurrency?->id;
            $card->attachments = json_encode([]);
            $card->created_by = $defaultUser?->id;

            // Translations
            $card->translateOrNew('ar')->name = 'بطاقة مورد - ' . ($supplier->translate('ar')->name ?? '');
            $card->translateOrNew('en')->name = 'Supplier Card - ' . ($supplier->translate('en')->name ?? '');
            $card->translateOrNew('ar')->trade_name = $supplier->translate('ar')->name ?? '';
            $card->translateOrNew('en')->trade_name = $supplier->translate('en')->name ?? '';
            $card->translateOrNew('ar')->notes = 'بطاقة تجريبية للمورد.';
            $card->translateOrNew('en')->notes = 'Sample supplier card.';

            $card->save();
        }

        $this->command?->info('تم إنشاء بطاقات الموردين التجريبية وربطها بالموردين الموجودين.');
    }
}

