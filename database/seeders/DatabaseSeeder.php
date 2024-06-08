<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Utility;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
   
            $this->call(UsersTableSeeder::class);
            $this->call(PlansTableSeeder::class);
            $this->call(AiTemplateSeeder::class);
    
            Utility::languagecreate();
            // Utility::defaultEmail();
    
        Artisan::call('module:migrate LandingPage');
        Artisan::call('module:seed LandingPage');
        
    }
}
