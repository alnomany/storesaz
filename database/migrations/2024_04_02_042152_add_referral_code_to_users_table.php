<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'referral_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('referral_code')->default(0)->after('is_enable_login');
                $table->integer('used_referral_code')->default(0)->after('referral_code');
                $table->integer('commission_amount')->default(0)->after('used_referral_code');
            });
        }
        if (Schema::hasColumn('users', 'referral_code')) {
            $users = \App\Models\User::where('type','Owner')->get();
            if($users->count() == null || $users->count() == 0){
                foreach($users as $user){
                    do {
                        $refferal_code = rand(100000 , 999999);
                        $checkCode = \App\Models\User::where('type','Owner')->where('referral_code', $refferal_code)->get();
                    }
                    while ($checkCode->count());

                    $user->referral_code = $refferal_code;
                    $user->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
