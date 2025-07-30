<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $this->adminCreate();
            DB::commit();
            $this->command->info('Admin successfully seeded');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->info($th->getMessage());
        }
    }

    public function adminCreate()
    {
        $admin = new Admin();
        $admin->name = 'Supper Adin';
        $admin->email = 'admin@gmail.com';
        $admin->password = Hash::make('password');
        $admin->save();
    }
}
