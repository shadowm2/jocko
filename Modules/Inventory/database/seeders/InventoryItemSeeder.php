<?php

namespace Modules\Inventory\Database\Seeders;

use App\Helpers\Utils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\Dashboard\Enums\CategoryIcon;
use Modules\Dashboard\Enums\CategoryType;
use Modules\Dashboard\Models\Category;
use Modules\Dashboard\Models\Media;
use Modules\Inventory\Models\Brand;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\ItemBrand;
use Modules\Inventory\Models\UnitGroup;
use Random\RandomException;
use Symfony\Component\Mime\MimeTypes;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws \Exception
     */
    public function run(): void
    {
        $this->seedCategories();
        $this->seedItems();
        $this->seedItemBrands();
    }

    private function seedItemBrands(): void
    {
        $items = Item::all();
        $brands = Brand::all();
        $items->each(/**
         * @throws RandomException
         */ function ($item) use ($brands) {
            $itemBrands = $brands->random(random_int(1, 5));
            foreach ($itemBrands as $itemBrand) {
                $slug = $item->name.' '.$itemBrand->name;
                if (ItemBrand::where([
                    ['item_id', $item->id],
                    ['brand_id', $itemBrand->id],
                ])->exists()) {
                    continue;
                }
                $itemBrand = ItemBrand::factory()
                    ->for($item)
                    ->for($itemBrand)
                    ->make([
                        'slug' => Utils::generateUniqueSlug($slug, ItemBrand::class),
                    ]);
                $itemBrand->save();
            }
        });
    }

    /**
     * @throws \Exception
     */
    private function seedItems(): void
    {
        $unitGroups = UnitGroup::all();
        $categories = Category::all();
        if ($unitGroups->isEmpty() || $categories->isEmpty()) {
            throw new \Exception('No Unit Groups or Categories');
        }

        $sampleImagesDirectory = database_path('seeders/data/images/items');
        if (! file_exists($sampleImagesDirectory) || ! is_dir($sampleImagesDirectory)) {
            throw new \Exception("Folder ($sampleImagesDirectory) does not exist or is not readable");
        }
        $images = scandir($sampleImagesDirectory);
        foreach ($images as $image) {
            if (in_array($image, ['.', '..'])) {
                continue;
            }
            $imageAlreadyUsed = Media::where([
                ['model_type', Item::class],
                ['collection', 'images'],
                ['original_name', $image],
            ])->exists();
            if ($imageAlreadyUsed) {
                continue;
            }

            $item = Item::factory()
                ->for($unitGroups->random())
                ->for($categories->random())
                ->make();

            $item->slug = Utils::generateUniqueSlug($item->name, Item::class);
            $item->save();
            $imageSrc = "$sampleImagesDirectory/$image";
            $imageDest = Storage::disk('public')->path("inventory/items/{$item->slug}/").$image;
            $url = "inventory/items/{$item->slug}/$image";
            if (! is_dir(dirname($imageDest))) {
                mkdir(dirname($imageDest), 0755, true);
            }
            copy($imageSrc, $imageDest);
            $item->images()->create([
                'path' => $url,
                'collection' => 'images',
                'disk' => 'public',
                'mime_type' => MimeTypes::getDefault()->guessMimeType($imageSrc),
                'original_name' => $image,
                'size' => getimagesize($imageSrc)[0],
            ]);
        }
    }

    private function seedCategories(): void
    {
        $categories = [
            [
                'name' => 'قطعات یدکی',
                'slug' => 'spare-parts',
                'icon' => 'wrench',
                'children' => [
                    [
                        'name' => 'موتور',
                        'slug' => 'engine',
                        'icon' => 'cog',
                        'children' => [
                            [
                                'name' => 'قطعات داخلی موتور',
                                'slug' => 'engine-internal-parts',
                                'icon' => 'settings',
                            ],
                            [
                                'name' => 'سرسیلندر',
                                'slug' => 'cylinder-head',
                                'icon' => 'circle-dot',
                            ],
                            [
                                'name' => 'واشر و کاسه‌نمد',
                                'slug' => 'gaskets-and-seals',
                                'icon' => 'layers',
                            ],
                            [
                                'name' => 'پیستون و رینگ',
                                'slug' => 'pistons-and-rings',
                                'icon' => 'circle',
                            ],
                            [
                                'name' => 'تسمه و زنجیر موتور',
                                'slug' => 'engine-belts-and-chains',
                                'icon' => 'link',
                            ],
                            [
                                'name' => 'سوپاپ و متعلقات',
                                'slug' => 'valves-and-parts',
                                'icon' => 'circle-chevron-down',
                            ],
                        ],
                    ],

                    [
                        'name' => 'سیستم سوخت‌رسانی',
                        'slug' => 'fuel-system',
                        'icon' => 'fuel',
                        'children' => [
                            [
                                'name' => 'پمپ بنزین',
                                'slug' => 'fuel-pump',
                                'icon' => 'droplet',
                            ],
                            [
                                'name' => 'انژکتور',
                                'slug' => 'injectors',
                                'icon' => 'spray-can',
                            ],
                            [
                                'name' => 'فیلتر سوخت',
                                'slug' => 'fuel-filters',
                                'icon' => 'adjustments-horizontal',
                            ],
                        ],
                    ],

                    [
                        'name' => 'سیستم خنک‌کننده',
                        'slug' => 'cooling-system',
                        'icon' => 'fan',
                        'children' => [
                            [
                                'name' => 'رادیاتور',
                                'slug' => 'radiators',
                                'icon' => 'panel-top',
                            ],
                            [
                                'name' => 'واتر پمپ',
                                'slug' => 'water-pumps',
                                'icon' => 'waves-horizontal',
                            ],
                            [
                                'name' => 'ترموستات',
                                'slug' => 'thermostats',
                                'icon' => 'thermometer',
                            ],
                            [
                                'name' => 'فن و متعلقات',
                                'slug' => 'fans',
                                'icon' => 'fan',
                            ],
                        ],
                    ],

                    [
                        'name' => 'سیستم برق و الکترونیک',
                        'slug' => 'electrical-and-electronics',
                        'icon' => 'zap',
                        'children' => [
                            [
                                'name' => 'باتری',
                                'slug' => 'batteries',
                                'icon' => 'battery',
                            ],
                            [
                                'name' => 'دینام',
                                'slug' => 'alternators',
                                'icon' => 'refresh-cw',
                            ],
                            [
                                'name' => 'استارت',
                                'slug' => 'starters',
                                'icon' => 'power',
                            ],
                            [
                                'name' => 'شمع',
                                'slug' => 'spark-plugs',
                                'icon' => 'zap',
                            ],
                            [
                                'name' => 'کوئل',
                                'slug' => 'ignition-coils',
                                'icon' => 'radio',
                            ],
                            [
                                'name' => 'فیوز و رله',
                                'slug' => 'fuses-and-relays',
                                'icon' => 'square-stack',
                            ],
                            [
                                'name' => 'سنسورها',
                                'slug' => 'sensors',
                                'icon' => 'radar',
                            ],
                            [
                                'name' => 'لامپ و روشنایی',
                                'slug' => 'bulbs-and-lighting',
                                'icon' => 'lightbulb',
                            ],
                        ],
                    ],

                    [
                        'name' => 'سیستم انتقال قدرت',
                        'slug' => 'drivetrain',
                        'icon' => 'cog',
                        'children' => [
                            [
                                'name' => 'کلاچ',
                                'slug' => 'clutch',
                                'icon' => 'disc',
                            ],
                            [
                                'name' => 'گیربکس',
                                'slug' => 'transmission',
                                'icon' => 'cog',
                            ],
                            [
                                'name' => 'پلوس',
                                'slug' => 'cv-axles',
                                'icon' => 'move-horizontal',
                            ],
                            [
                                'name' => 'دیفرانسیل',
                                'slug' => 'differential',
                                'icon' => 'settings',
                            ],
                        ],
                    ],

                    [
                        'name' => 'سیستم ترمز',
                        'slug' => 'braking-system',
                        'icon' => 'circle-stop',
                        'children' => [
                            [
                                'name' => 'لنت ترمز',
                                'slug' => 'brake-pads',
                                'icon' => 'square',
                            ],
                            [
                                'name' => 'دیسک و کاسه ترمز',
                                'slug' => 'discs-and-drums',
                                'icon' => 'disc',
                            ],
                            [
                                'name' => 'کالیپر',
                                'slug' => 'calipers',
                                'icon' => 'grip',
                            ],
                            [
                                'name' => 'پمپ ترمز',
                                'slug' => 'brake-master-cylinders',
                                'icon' => 'cylinder',
                            ],
                        ],
                    ],

                    [
                        'name' => 'سیستم تعلیق و فرمان',
                        'slug' => 'suspension-and-steering',
                        'icon' => 'move',
                        'children' => [
                            [
                                'name' => 'کمک‌فنر',
                                'slug' => 'shock-absorbers',
                                'icon' => 'arrow-down-up',
                            ],
                            [
                                'name' => 'فنر',
                                'slug' => 'springs',
                                'icon' => 'waves-horizontal',
                            ],
                            [
                                'name' => 'طبق و بوش',
                                'slug' => 'control-arms-and-bushings',
                                'icon' => 'git-branch',
                            ],
                            [
                                'name' => 'سیبک و میل‌فرمان',
                                'slug' => 'ball-joints-and-tie-rods',
                                'icon' => 'git-merge',
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'روغن و روان‌کننده‌ها',
                'slug' => 'oils-and-lubricants',
                'icon' => 'droplets',
                'children' => [
                    [
                        'name' => 'روغن موتور',
                        'slug' => 'engine-oil',
                        'icon' => 'droplet',
                    ],
                    [
                        'name' => 'روغن گیربکس',
                        'slug' => 'transmission-oil',
                        'icon' => 'droplet',
                    ],
                    [
                        'name' => 'روغن هیدرولیک',
                        'slug' => 'hydraulic-oil',
                        'icon' => 'droplet',
                    ],
                    [
                        'name' => 'روغن ترمز',
                        'slug' => 'brake-fluid',
                        'icon' => 'droplet',
                    ],
                    [
                        'name' => 'گریس',
                        'slug' => 'grease',
                        'icon' => 'box',
                    ],
                ],
            ],

            [
                'name' => 'فیلترها',
                'slug' => 'filters',
                'icon' => 'adjustments-horizontal',
                'children' => [
                    [
                        'name' => 'فیلتر روغن',
                        'slug' => 'oil-filters',
                        'icon' => 'adjustments-horizontal',
                    ],
                    [
                        'name' => 'فیلتر هوا',
                        'slug' => 'air-filters',
                        'icon' => 'wind',
                    ],
                    [
                        'name' => 'فیلتر سوخت',
                        'slug' => 'fuel-filters',
                        'icon' => 'adjustments-horizontal',
                    ],
                    [
                        'name' => 'فیلتر کابین',
                        'slug' => 'cabin-filters',
                        'icon' => 'air-vent',
                    ],
                ],
            ],

            [
                'name' => 'مایعات و مواد مصرفی',
                'slug' => 'fluids-and-consumables',
                'icon' => 'flask-conical',
                'children' => [
                    [
                        'name' => 'ضدیخ و ضدجوش',
                        'slug' => 'coolants',
                        'icon' => 'thermometer',
                    ],
                    [
                        'name' => 'آب رادیاتور',
                        'slug' => 'radiator-water',
                        'icon' => 'droplets',
                    ],
                    [
                        'name' => 'مایع شیشه‌شویی',
                        'slug' => 'windshield-washer-fluid',
                        'icon' => 'droplets',
                    ],
                    [
                        'name' => 'چسب و درزگیر',
                        'slug' => 'adhesives-and-sealants',
                        'icon' => 'paintbrush',
                    ],
                    [
                        'name' => 'اسپری‌ها',
                        'slug' => 'sprays',
                        'icon' => 'spray-can',
                    ],
                ],
            ],

            [
                'name' => 'ابزار و تجهیزات',
                'slug' => 'tools-and-equipment',
                'icon' => 'wrench',
                'children' => [
                    [
                        'name' => 'ابزار دستی',
                        'slug' => 'hand-tools',
                        'icon' => 'hammer',
                        'children' => [
                            [
                                'name' => 'آچار',
                                'slug' => 'wrenches',
                                'icon' => 'wrench',
                            ],
                            [
                                'name' => 'پیچ‌گوشتی',
                                'slug' => 'screwdrivers',
                                'icon' => 'wrench-screwdriver',
                            ],
                            [
                                'name' => 'انبر',
                                'slug' => 'pliers',
                                'icon' => 'grip',
                            ],
                            [
                                'name' => 'آچار بکس',
                                'slug' => 'socket-tools',
                                'icon' => 'wrench',
                            ],
                        ],
                    ],
                    [
                        'name' => 'ابزار برقی',
                        'slug' => 'power-tools',
                        'icon' => 'drill',
                    ],
                    [
                        'name' => 'ابزار تخصصی خودرو',
                        'slug' => 'automotive-specialty-tools',
                        'icon' => 'car-front',
                    ],
                    [
                        'name' => 'تجهیزات تعمیرگاهی',
                        'slug' => 'garage-equipment',
                        'icon' => 'warehouse',
                    ],
                ],
            ],

            [
                'name' => 'لاستیک و چرخ',
                'slug' => 'tires-and-wheels',
                'icon' => 'circle',
                'children' => [
                    [
                        'name' => 'لاستیک',
                        'slug' => 'tires',
                        'icon' => 'circle',
                    ],
                    [
                        'name' => 'رینگ',
                        'slug' => 'wheels',
                        'icon' => 'circle-dot',
                    ],
                    [
                        'name' => 'والو و متعلقات',
                        'slug' => 'valves-and-accessories',
                        'icon' => 'circle-dot',
                    ],
                ],
            ],

            [
                'name' => 'لوازم جانبی خودرو',
                'slug' => 'car-accessories',
                'icon' => 'car',
                'children' => [
                    [
                        'name' => 'لوازم برقی جانبی',
                        'slug' => 'electrical-accessories',
                        'icon' => 'zap',
                    ],
                    [
                        'name' => 'لوازم داخلی',
                        'slug' => 'interior-accessories',
                        'icon' => 'armchair',
                    ],
                    [
                        'name' => 'لوازم خارجی',
                        'slug' => 'exterior-accessories',
                        'icon' => 'car-front',
                    ],
                ],
            ],
        ];

        $itemCategory = Category::updateOrCreate([
            'slug' => 'items',
        ], [
            'depth' => 0,
            'name' => 'لوازم و کالا ها',
            'type' => CategoryType::Item,
            'icon' => CategoryIcon::User,
        ]);

        foreach ($categories as $category) {
            $this->walkCategory($category, $itemCategory);
        }
    }

    private function walkCategory(array $data, Category $parent): void
    {

        $catData = [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'type' => $parent->type,
            'icon' => $data['icon'],
            'depth' => $parent->depth + 1,
            'is_active' => 1,
            'parent_id' => $parent->id,
        ];
        $category = Category::updateOrCreate([
            'slug' => $data['slug'],
        ], $catData);
        foreach ($data['children'] ?? [] as $child) {
            $this->walkCategory($child, $category);
        }
    }
}
