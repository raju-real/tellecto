<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Agent;
use App\Models\Business;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::insert([
            'name' => 'Mr. Admin',
            'type' => 'super-admin',
            'role_id' => 1,
            'username' => 'super-admin',
            'email' => 'su@tellecto.se',
            'password' => Hash::make('123456'),
            'is_active' => true,
            'created_at' => now()
        ]);


        Business::insert(
            [
                'company_name' => 'company_name',
                'org_no' => '1234',
                'vat_no' => '123',
                'contact_person' => 'contact_person',
                'business_type' => 'B2B',
                'website_url' => 'www.business.com',
                'first_name' => 'first_name',
                'last_name' => 'last_name',
                'phone' => '01822811531',
                'email' => 'business@tellecto.se',
                'street' => 'street',
                'city' => 'city',
                'zip_code' => '12345',
                'password' => Hash::make('123456'),
                'status' => true,
                'created_at' => now()
            ],
            [
                'company_name' => 'company_name 2',
                'org_no' => '1234',
                'vat_no' => '123',
                'contact_person' => 'contact_person',
                'business_type' => 'B2B',
                'website_url' => 'www.business.com',
                'first_name' => 'first_name',
                'last_name' => 'last_name',
                'phone' => '01822811531',
                'email' => 'business2@tellecto.se',
                'street' => 'street',
                'city' => 'city',
                'zip_code' => '12345',
                'password' => Hash::make('123456'),
                'status' => true,
                'created_at' => now()
            ],
        );

        Agent::insert([
            'business_id' => '1',
            'agent_code' => '1234',
            'personal_id' => '12345678901',
            'manager_name' => 'Manager',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'phone' => '01822811531',
            'email' => 'agent@tellecto.se',
            'street' => 'street',
            'city' => 'city',
            'zip_code' => '12345',
            'password' => Hash::make('123456'),
            'status' => true,
            'created_at' => now()
        ]);
    }
}
