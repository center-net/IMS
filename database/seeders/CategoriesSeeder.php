<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $createdCount = 0;

        // Predefined multi-level category tree (with Arabic/English names)
        $tree = [
            [
                'en' => 'Electronics',
                'ar' => 'الإلكترونيات',
                'children' => [
                    [
                        'en' => 'Phones',
                        'ar' => 'الهواتف',
                        'children' => [
                            ['en' => 'Smartphones', 'ar' => 'هواتف ذكية'],
                            ['en' => 'Feature Phones', 'ar' => 'هواتف بسيطة'],
                        ],
                    ],
                    [
                        'en' => 'Laptops',
                        'ar' => 'الحواسيب المحمولة',
                        'children' => [
                            ['en' => 'Ultrabooks', 'ar' => 'ألترا بوك'],
                            ['en' => 'Gaming Laptops', 'ar' => 'حواسيب ألعاب'],
                        ],
                    ],
                    [
                        'en' => 'Accessories',
                        'ar' => 'إكسسوارات',
                        'children' => [
                            ['en' => 'Chargers', 'ar' => 'شواحن'],
                            ['en' => 'Cables', 'ar' => 'كوابل'],
                        ],
                    ],
                ],
            ],
            [
                'en' => 'Clothing',
                'ar' => 'الملابس',
                'children' => [
                    [
                        'en' => 'Men',
                        'ar' => 'رجالي',
                        'children' => [
                            ['en' => 'Shirts', 'ar' => 'قمصان'],
                            ['en' => 'Pants', 'ar' => 'بنطال'],
                        ],
                    ],
                    [
                        'en' => 'Women',
                        'ar' => 'نسائي',
                        'children' => [
                            ['en' => 'Dresses', 'ar' => 'فساتين'],
                            ['en' => 'Blouses', 'ar' => 'بلوزات'],
                        ],
                    ],
                    [
                        'en' => 'Kids',
                        'ar' => 'أطفال',
                        'children' => [
                            ['en' => 'T-Shirts', 'ar' => 'تيشيرتات'],
                            ['en' => 'Shorts', 'ar' => 'شورتات'],
                        ],
                    ],
                ],
            ],
            [
                'en' => 'Home & Kitchen',
                'ar' => 'المنزل والمطبخ',
                'children' => [
                    ['en' => 'Appliances', 'ar' => 'الأجهزة المنزلية'],
                    ['en' => 'Cookware', 'ar' => 'أدوات الطبخ'],
                    ['en' => 'Storage', 'ar' => 'التخزين'],
                ],
            ],
            [
                'en' => 'Sports',
                'ar' => 'الرياضة',
                'children' => [
                    ['en' => 'Fitness', 'ar' => 'لياقة'],
                    ['en' => 'Outdoor', 'ar' => 'أنشطة خارجية'],
                    ['en' => 'Team Sports', 'ar' => 'رياضات جماعية'],
                ],
            ],
            [
                'en' => 'Books',
                'ar' => 'الكتب',
                'children' => [
                    ['en' => 'Fiction', 'ar' => 'روايات'],
                    ['en' => 'Non-Fiction', 'ar' => 'غير روايات'],
                    ['en' => 'Children', 'ar' => 'أطفال'],
                ],
            ],
        ];

        $createdCount += $this->seedTree($tree);

        // If we haven't reached 50 categories, add generic ones at root level
        for ($i = $createdCount + 1; $i <= 50; $i++) {
            $this->createCategory('Category ' . $i, 'صنف ' . $i);
            $createdCount++;
        }

        $this->command?->info("Inserted {$createdCount} categories including multi-level nesting.");
    }

    /**
     * Seed a nested tree structure.
     *
     * @param array<int, array<string, mixed>> $nodes
     * @param int|null $parentId
     * @return int number of created categories
     */
    private function seedTree(array $nodes, ?int $parentId = null): int
    {
        $count = 0;
        foreach ($nodes as $node) {
            $cat = $this->createCategory($node['en'], $node['ar'], $parentId);
            $count++;
            $children = $node['children'] ?? [];
            if (!empty($children) && is_array($children)) {
                $count += $this->seedTree($children, $cat->id);
            }
        }
        return $count;
    }

    /**
     * Create a single category with translations.
     */
    private function createCategory(string $enName, string $arName, ?int $parentId = null): Category
    {
        $existing = Category::query()
            ->whereTranslation('name', $enName, 'en')
            ->orWhereTranslation('name', $arName, 'ar')
            ->first();

        if ($existing) {
            if ($existing->parent_id !== $parentId) {
                $existing->parent_id = $parentId;
            }
            $existing->translateOrNew('en')->name = $enName;
            $existing->translateOrNew('ar')->name = $arName;
            $existing->save();
            return $existing;
        }

        $cat = new Category();
        $cat->parent_id = $parentId;
        $cat->save();
        $cat->translateOrNew('en')->name = $enName;
        $cat->translateOrNew('ar')->name = $arName;
        $cat->save();
        return $cat;
    }
}
