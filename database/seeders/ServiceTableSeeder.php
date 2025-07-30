<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            $datas = $this->serviceLists();

            foreach ($datas as $data) {
                $this->serviceCreate($data);
            }

            DB::commit();

            $this->command->info('Services successfully seeded');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->error($th->getMessage());
        }
    }

    private function serviceCreate($data)
    {
        $data = (object) $data;

        $service = new Service();
        $service->name = $data->name;
        $service->description = $data->description;
        $service->price = $data->price;
        $service->status = $data->status;
        $service->save();
        return true;
    }

    private function serviceLists()
    {
        $data =  [
            [
                "name" => "Website Development",
                "price" => 500.00,
                "description" => "Responsive business website.",
                "status" => "Active"
            ],
            [
                "name" => "Logo Design",
                "price" => 80.00,
                "description" => "Custom logo with 3 revisions.",
                "status" => "Active"
            ],
            [
                "name" => "SEO Optimization",
                "price" => 150.00,
                "description" => "Improve search engine ranking.",
                "status" => "Active"
            ],
            [
                "name" => "Mobile App UI Design",
                "price" => 200.00,
                "description" => "Clean and modern UI for apps.",
                "status" => "Active"
            ],
            [
                "name" => "Social Media Marketing",
                "price" => 300.00,
                "description" => "Monthly campaign for social growth.",
                "status" => "Active"
            ],
            [
                "name" => "Bug Fixing",
                "price" => 70.00,
                "description" => "Fix minor issues in code.",
                "status" => "Active"
            ],
            [
                "name" => "Domain & Hosting",
                "price" => 120.00,
                "description" => "1-year domain and hosting package.",
                "status" => "Active"
            ]
        ];

        return $data;
    }
}
