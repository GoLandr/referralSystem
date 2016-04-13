<?php

namespace App\Managers;

use Illuminate\Database\DatabaseManager;

class ReferralKeyManager
{
    /** @var DatabaseManager */
    protected $db;

    const DEFAULT_LENGTH = 10;

    /**
     * ReferralKeyManager constructor.
     * @param DatabaseManager $db
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Generate new unique referral key
     *
     * @param int $length
     *
     * @return mixed
     */
    public function generate($length = self::DEFAULT_LENGTH)
    {
        do {
            $key = str_random($length);
        } while ($this->check($key));

        return $key;
    }

    /**
     * Check if key exists in DB
     *
     * @param $key
     *
     * @return bool
     */
    public function check($key)
    {
        return (bool)$this->db->connection()
                          ->table('users')
                          ->where('referral_key', $key)
                          ->count();
    }

    /**
     * Find User ID by referral key
     *
     * @param $key
     *
     * @return mixed
     */
    public function findUserIdByReferralKey($key)
    {
        return $this->db->connection()
                    ->table('users')
                    ->where('referral_key', $key)
                    ->value('id');
    }
}