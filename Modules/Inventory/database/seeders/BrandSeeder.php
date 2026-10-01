<?php

namespace Modules\Inventory\Database\Seeders;

use App\Helpers\Utils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\Inventory\Models\Brand;
use Symfony\Component\Mime\MimeTypes;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            // Germany
            'Bosch',
            'MANN-FILTER',
            'MAHLE',
            'Schaeffler',
            'LuK',
            'INA',
            'FAG',
            'Continental',
            'ATE',
            'Hella',
            'Brembo',
            'ZF',
            'TRW',
            'Bilstein',
            'Febi Bilstein',
            'Pierburg',
            'Hengst',
            'Meyle',
            'Sachs',
            'VDO',

            // Japan
            'Denso',
            'NGK',
            'NTK',
            'Aisin',
            'KYB',
            'Akebono',
            'Nippon Seiki',
            'Hitachi',
            'Tokico',
            '555',
            'Koyo',
            'Nachi',
            'NWB',

            // USA
            'ACDelco',
            'Delphi',
            'Monroe',
            'Moog',
            'Gates',
            'Dayco',
            'Federal-Mogul',
            'Motorcraft',
            'Wix',
            'Champion',
            'Clevite',
            'Timken',
            'Fel-Pro',
            'Walker',
            'Dana',

            // France
            'Valeo',
            'Mecaplast',
            'SNR',
            'Purflux',
            'Sasic',

            // Italy
            'Magneti Marelli',
            'Marelli',
            'UFI Filters',
            'Dayco',
            'Metelli',
            'Cifam',
            'Dolz',
            'OCAP',
            'Ruville',

            // UK
            'Comline',
            'Delphi Technologies',
            'AP Racing',
            'GKN',
            'Borg & Beck',

            // Spain
            'MGA',
            'FAGOR',
            'FTE',

            // Sweden
            'SKF',
            'Husqvarna Automotive',
            'Haldex',

            // South Korea
            'Hyundai Mobis',
            'Mando',
            'CTR',
            'Sangsin',
            'Valeo Korea',

            // China
            'CATL',
            'Wanxiang',
            'GSP',
            'ChaoYang',
            'Hengst China',

            // Turkey
            'Maysan Mando',
            'Teknorot',
            'İBRAŞ',
            'Sampa',

            // Iran
            [
                'name' => 'ایساکو',
                'slug' => 'isaco',
            ],
            [
                'name' => 'سایپا یدک',
                'slug' => 'saipayadak',
            ],
            [
                'name' => 'کروز',
                'slug' => 'crouse',
            ],
            [
                'name' => 'عظام',
                'slug' => 'ezam',
            ],
            [
                'name' => 'مدرن',
                'slug' => 'modern',
            ],
            [
                'name' => 'پارس نیکان',
                'slug' => 'pars nikan',
            ],
            [
                'name' => 'امکو',
                'slug' => 'mco',
            ],
            [
                'name' => 'پارت لاستیک',
                'slug' => 'part lastic',
            ],
        ];
        sort($brands);
        foreach ($brands as $brandName) {
            if (is_array($brandName)) {
                $slug = $brandName['slug'];
                $brandName = $brandName['name'];
            } else {
                $slug = Utils::generateBaseSlug($brandName);
            }
            $data = Brand::factory()->make([
                'slug' => $slug,
                'name' => $brandName,
            ]);
            /** @var Brand $brand */
            $brand = Brand::updateOrCreate([
                'name' => $brandName,
            ], $data->toArray());

            $srcPath = database_path('seeders/data/images/brands/');
            $files = scandir($srcPath, SCANDIR_SORT_DESCENDING);
            $files = collect($files)->filter(function ($fileName) use ($brand) {
                $brandFileName = preg_replace('/ [&]/', '', $brand->slug);
                $brandFileName = basename(strtolower(
                    str_replace(' ', '-', $brandFileName)
                ));

                $regex = '/^'.$brandFileName.'\..*$/';
                $matches = preg_match($regex, basename(strtolower($fileName)));

                return $matches > 0;
            });
            if ($files->isEmpty()) {
                $brandFileName = preg_replace('/ [&]/', '', $brand->slug);
                $brandFileName = basename(strtolower(
                    str_replace(' ', '-', $brandFileName)
                ));
                dd('wtf no image for brand '.$brandFileName.' at this path: '.$srcPath);
            }
            $name = $files->first();
            $path = "inventory/brands/$name";
            Storage::disk('public')->makeDirectory('inventory/brands');
            copy("$srcPath/$name", Storage::disk('public')->path('inventory/brands/'.$name));
            $brand->logo()->updateOrCreate([
                'disk' => 'public',
            ], [
                'collection' => 'images',
                'path' => $path,
                'original_name' => basename($path),
                'mime_type' => MimeTypes::getDefault()->guessMimeType($srcPath.'/'.$name),
                'size' => filesize($srcPath.'/'.$name),
            ]);
        }
    }
}
