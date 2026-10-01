<?php

namespace Modules\Car\Database\Seeders;

use App\Helpers\Utils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\Car\Models\CarCompany;
use Symfony\Component\Mime\MimeTypes;

class CarCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carManufacturers = [
            ['name' => 'اینفینیتی', 'name_en' => 'Infiniti'],
            ['name' => 'تویوتا', 'name_en' => 'Toyota'],
            ['name' => 'لکسوس', 'name_en' => 'Lexus'],
            ['name' => 'دایهاتسو', 'name_en' => 'Daihatsu'],
            ['name' => 'نیسان', 'name_en' => 'Nissan'],
            ['name' => 'اینفینیتی', 'name_en' => 'Infiniti'],
            ['name' => 'هوندا', 'name_en' => 'Honda'],
            ['name' => 'آکورا', 'name_en' => 'Acura'],
            ['name' => 'مزدا', 'name_en' => 'Mazda'],
            ['name' => 'میتسوبیشی', 'name_en' => 'Mitsubishi'],
            ['name' => 'سوبارو', 'name_en' => 'Subaru'],
            ['name' => 'سوزوکی', 'name_en' => 'Suzuki'],

            ['name' => 'هیوندای', 'name_en' => 'Hyundai'],
            ['name' => 'کیا', 'name_en' => 'Kia'],
            ['name' => 'جنسیس', 'name_en' => 'Genesis'],
            ['name' => 'سانگ‌یانگ', 'name_en' => 'SsangYong'],
            ['name' => 'دوو', 'name_en' => 'Daewoo'],

            ['name' => 'فولکس‌واگن', 'name_en' => 'Volkswagen'],
            ['name' => 'آئودی', 'name_en' => 'Audi'],
            ['name' => 'پورشه', 'name_en' => 'Porsche'],
            ['name' => 'بی‌ام‌و', 'name_en' => 'BMW'],
            ['name' => 'مینی', 'name_en' => 'MINI'],
            ['name' => 'مرسدس بنز', 'name_en' => 'Mercedes-Benz'],
            ['name' => 'اسمارت', 'name_en' => 'Smart'],
            ['name' => 'اوپل', 'name_en' => 'Opel'],

            ['name' => 'فورد', 'name_en' => 'Ford'],
            ['name' => 'شورولت', 'name_en' => 'Chevrolet'],
            ['name' => 'کادیلاک', 'name_en' => 'Cadillac'],
            ['name' => 'بیوک', 'name_en' => 'Buick'],
            ['name' => 'جی‌ام‌سی', 'name_en' => 'GMC'],
            ['name' => 'دوج', 'name_en' => 'Dodge'],
            ['name' => 'جیپ', 'name_en' => 'Jeep'],
            ['name' => 'تسلا', 'name_en' => 'Tesla'],

            ['name' => 'پژو', 'name_en' => 'Peugeot'],
            ['name' => 'سیتروئن', 'name_en' => 'Citroën'],
            ['name' => 'رنو', 'name_en' => 'Renault'],
            ['name' => 'داسیا', 'name_en' => 'Dacia'],
            ['name' => 'بوگاتی', 'name_en' => 'Bugatti'],
            ['name' => 'آلپاین', 'name_en' => 'Alpine'],

            ['name' => 'فیات', 'name_en' => 'Fiat'],
            ['name' => 'آلفارومئو', 'name_en' => 'Alfa Romeo'],
            ['name' => 'لانچیا', 'name_en' => 'Lancia'],
            ['name' => 'فراری', 'name_en' => 'Ferrari'],
            ['name' => 'لامبورگینی', 'name_en' => 'Lamborghini'],
            ['name' => 'مازراتی', 'name_en' => 'Maserati'],
            ['name' => 'پاگانی', 'name_en' => 'Pagani'],

            ['name' => 'ولوو', 'name_en' => 'Volvo'],
            ['name' => 'ساب', 'name_en' => 'Saab'],
            ['name' => 'کونیگزگ', 'name_en' => 'Koenigsegg'],
            ['name' => 'پولستار', 'name_en' => 'Polestar'],

            ['name' => 'تاتا', 'name_en' => 'Tata Motors'],
            ['name' => 'ماهیندرا', 'name_en' => 'Mahindra'],
            ['name' => 'ماروتی سوزوکی', 'name_en' => 'Maruti Suzuki'],

            ['name' => 'چری', 'name_en' => 'Chery'],
            ['name' => 'جک', 'name_en' => 'JAC'],
            ['name' => 'جیلی', 'name_en' => 'Geely'],
            ['name' => 'ام‌جی', 'name_en' => 'MG'],
            ['name' => 'گریت وال', 'name_en' => 'Great Wall'],
            ['name' => 'هاوال', 'name_en' => 'Haval'],
            ['name' => 'بی‌وای‌دی', 'name_en' => 'BYD'],
            ['name' => 'فاو', 'name_en' => 'FAW'],
            ['name' => 'دانگ‌فنگ', 'name_en' => 'Dongfeng'],
            ['name' => 'چانگان', 'name_en' => 'Changan'],
            ['name' => 'گک', 'name_en' => 'GAC'],
            ['name' => 'بایک', 'name_en' => 'BAIC'],
            ['name' => 'لیفان', 'name_en' => 'Lifan'],

            ['name' => 'ایران خودرو', 'name_en' => 'IKCO'],
            ['name' => 'سایپا', 'name_en' => 'SAIPA'],
            ['name' => 'پارس خودرو', 'name_en' => 'Pars Khodro'],
            ['name' => 'کرمان موتور', 'name_en' => 'Kerman Motor'],
            ['name' => 'بهمن موتور', 'name_en' => 'Bahman Motor'],
            ['name' => 'مدیران خودرو', 'name_en' => 'MVM'],
            ['name' => 'فردا موتورز', 'name_en' => 'Farda Motors'],
            ['name' => 'ماموت خودرو', 'name_en' => 'Mammut Khodro'],
        ];

        usort($carManufacturers, fn ($a, $b) => strcmp($a['name'], $b['name']));

        foreach ($carManufacturers as $manufacturer) {
            $slug = Utils::generateBaseSlug($manufacturer['name_en']);
            $carCompany = CarCompany::updateOrCreate([
                'slug' => $slug,
            ], [
                'name' => $manufacturer['name'],
            ]);

            $imagesPath = database_path('seeders/data/images/companies/');
            $files = scandir($imagesPath);

            $files = collect($files)->filter(function ($fileName) use ($carCompany) {
                $companyFileName = preg_replace('/ [&]/', '', $carCompany->slug);
                $companyFileName = basename(strtolower(
                    str_replace(' ', '-', $companyFileName)
                ));

                $regex = '/^'.$companyFileName.'\..*$/';
                $matches = preg_match($regex, basename(strtolower($fileName)));

                return $matches > 0;
            });
            if ($files->isEmpty()) {
                $companyFileName = preg_replace('/ [&]/', '', $carCompany->slug);
                $companyFileName = basename(strtolower(
                    str_replace(' ', '-', $companyFileName)
                ));
                dd('wtf', $carCompany->name, $companyFileName);
            }
            $path = $files->first();
            $destPath = Storage::disk('public')->path('car/companies');
            if (! file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            copy(database_path("seeders/data/images/companies/$path"), "$destPath/$path");
            $carCompany->logo()->updateOrCreate([
                'disk' => 'public',
            ], [
                'collection' => 'images',
                'path' => "car/companies/$path",
                'original_name' => basename($path),
                'mime_type' => MimeTypes::getDefault()->guessMimeType("$destPath/$path"),
                'size' => filesize($destPath.'/'.$path),
            ]);
        }

    }
}
