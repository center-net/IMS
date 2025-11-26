<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use Illuminate\Support\Carbon;

class OffersSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'ar' => 'عرض الافتتاح',
                'en' => 'Opening Offer',
                'price' => 49.99,
                'original_price' => 79.99,
                'start_date' => Carbon::now()->toDateString(),
                'end_date' => Carbon::now()->addMonth()->toDateString(),
            ],
            [
                'ar' => 'عرض نهاية الأسبوع',
                'en' => 'Weekend Offer',
                'price' => 19.99,
                'original_price' => 29.99,
                'start_date' => Carbon::now()->toDateString(),
                'end_date' => Carbon::now()->addWeeks(2)->toDateString(),
            ],
            [
                'ar' => 'عرض خاص',
                'en' => 'Special Offer',
                'price' => 99.00,
                'original_price' => 120.00,
                'start_date' => Carbon::now()->subWeek()->toDateString(),
                'end_date' => Carbon::now()->addWeeks(3)->toDateString(),
            ],
        ];

        foreach ($samples as $s) {
            $offer = new Offer();
            $offer->price = $s['price'];
            $offer->original_price = $s['original_price'];
            $offer->start_date = $s['start_date'];
            $offer->end_date = $s['end_date'];
            if (empty($offer->code)) {
                $offer->code = Offer::generateUniqueCode();
            }
            $offer->translateOrNew('ar')->name = $s['ar'];
            $offer->translateOrNew('en')->name = $s['en'];
            $offer->save();
        }

        $this->command?->info('تم إدراج/تحديث عروض تجريبية مع ترجمات الاسم.');
    }
}
