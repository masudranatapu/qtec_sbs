<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        try {
            DB::beginTransaction();
            $this->customerCreate();
            DB::commit();
            $this->command->info('Customer successfully seeded.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->info($th->getMessage());
        }
    }

    public function customerCreate()
    {
        $customer = new User();
        $customer->name = 'Cusomer';
        $customer->email = 'customer@gmail.com';
        $customer->password = Hash::make('password');
        $customer->save();
    }
}
