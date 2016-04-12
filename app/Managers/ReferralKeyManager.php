<?php

namespace App\Managers;

use Illuminate\Support\Facades\DB;

class ReferralKeyManager
{
    const DEFAULT_LENGTH = 10;

    /**
     * Generate new unique referral key
     *
     * @param int $length
     *
     * @return mixed
     */
    public static function generate($length = self::DEFAULT_LENGTH)
    {
        do {
            $key = str_random($length);
        } while (self::check($key));

        return $key;
    }

    /**
     * Check if key exists in DB
     *
     * @param $key
     *
     * @return bool
     */
    public static function check($key)
    {
        return (bool)DB::table('users')->where('referral_key', $key)
            ->count();
    }

    /**
     * Find User ID by referral key
     *
     * @param $key
     *
     * @return mixed
     */
    public static function findUserIdByReferralKey($key)
    {
        return DB::table('users')->where('referral_key', $key)->value('id');
    }
}