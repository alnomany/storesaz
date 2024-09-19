<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeederNew extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $admin=User::where('email','Luxuryksa80s@gmail.com')->first();
        Utility::chartOfAccountData($admin);
        Utility::chartOfAccountTypeData($admin->id);
    }
}
