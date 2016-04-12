<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferralIdToUser extends Migration
{
    /**
     * Add referral_id field to users table
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->integer('referral_id')->unsigned()->nullable();

            $table->foreign('referral_id')
                  ->references('id')->on('users')
                  ->onDelete('SET NULL');
        });
    }

    /**
     * Remove referral_id field from users table
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropForeign('users_referral_id_foreign');

            $table->dropColumn('referral_id');
        });
    }
}
