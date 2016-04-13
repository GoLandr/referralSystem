<?php

namespace App\Managers;

use App\Payments;
use App\User;
use Illuminate\Database\DatabaseManager;

class PaymentManager
{
    const REFERRAL_PERCENT = 10;

    const REFERRAL_STEPS = 2;

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
     * Add amount to user (and recursively to referrals)
     *
     * @param $amount
     * @param User $user
     * @param int $step
     * @param int $payerID
     */
    public function add($amount, User $user, $step = 0, $payerID = 0)
    {
        $paymentAmount = $this->calcAmountByStep($amount, $step);

        if ($paymentAmount) {
            $payment = new Payments();
            $payment->amount = $paymentAmount;
            $payment->user_id = $user->id;
            $payment->payer_id = $payerID ? $payerID : $user->id;
            $payment->step = $step;
            $payment->save();

            $this->db->table('users')->where(['id' => $user->id])->increment('balance', $paymentAmount);

            if ($step < self::REFERRAL_STEPS && $user->referral_id) {
                self::add($paymentAmount, User::find($user->referral_id), ++$step, $user->id);
            }
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

    /**
     * Algorithm of calculating amount for different steps of referral system
     *    0 step: 100% (payer)
     *    1 step: 10% (1st step referral)
     *    2 step: 1% (2nd step referral)
     *
     * @param $amount
     * @param $step
     *
     * @return mixed
     */
    protected function calcAmountByStep($amount, $step)
    {
        return $step ? round($amount / self::REFERRAL_PERCENT, 2) : $amount;
    }
}