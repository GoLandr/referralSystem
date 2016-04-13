<?php

namespace App\Managers;

use App\Payments;
use App\User;
use Illuminate\Database\DatabaseManager;

class PaymentManager
{
    const BASE_PAYMENT_TYPE = 0;

    const REFERRAL_PAYMENT_TYPE = 1;

    const REFERRAL_PERCENT = 10;

    /** @var DatabaseManager */
    protected $db;

    /**
     * PaymentManager constructor.
     *
     * @param DatabaseManager $db
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * @param $amount
     * @param User $user
     */
    public function add($amount, User $user)
    {
        $payment = new Payments();
        $payment->amount = $amount;
        $payment->user_id = $user->id;
        $payment->payer_id = $user->id;
        $payment->type = self::BASE_PAYMENT_TYPE;
        $payment->save();

        $this->db->table('users')->where(['id' => $user->id])->increment('balance', $amount);

        if ($user->referral_id) {
            $referralAmount = round($amount / self::REFERRAL_PERCENT, 2);

            $payment = new Payments();
            $payment->amount = $referralAmount;
            $payment->user_id = $user->referral_id;
            $payment->payer_id = $user->id;
            $payment->type = self::REFERRAL_PAYMENT_TYPE;
            $payment->save();

            $this->db->table('users')->where(['id' => $user->referral_id])->increment('balance', $referralAmount);
        }
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getAllPaymentsForUser(User $user)
    {
        return $this->db->table('payments')
                        ->select('payments.*', 'payer.name as payer_name')
                        ->leftJoin('users as payer', 'payer.id', '=', 'payments.payer_id')
                        ->where('user_id', '=', $user->id)
                        ->get();
    }
}