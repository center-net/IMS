<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Representative;
use App\Models\RepresentativeCard;
use App\Models\User;

class RepresentativeCardsSeeder extends Seeder
{
    public function run(): void
    {
        $defaultUser = User::query()->first();

        foreach (Representative::all() as $rep) {
            $card = RepresentativeCard::firstOrNew(['representative_id' => $rep->id]);
            $card->representative_id = $rep->id;
            $card->code = $rep->code;
            $card->role = 'sales';
            $card->branch = 'Main Branch';
            $card->phone = '0599000000';
            $card->email = 'rep' . str_pad((string)$rep->id, 2, '0', STR_PAD_LEFT) . '@example.com';
            $card->commission_rate = 3.00;
            $card->commission_method = 'gross_sales';
            $card->commission_min = null;
            $card->commission_max = null;
            $card->status = 'active';
            $card->attachments = json_encode([]);
            $card->created_by = $defaultUser?->id;
            $card->save();

            $card->translateOrNew('ar')->name = 'بطاقة مندوب';
            $card->translateOrNew('ar')->notes = 'مناطق التغطية: وسط الضفة الغربية';
            $card->translateOrNew('en')->name = 'Sales Representative Card';
            $card->translateOrNew('en')->notes = 'Coverage areas: Central West Bank';
            $card->save();
        }
    }
}

