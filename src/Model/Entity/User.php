<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $ref_code
 * @property string $referred_by
 * @property string $balance
 * @property string $investment_fund
 * @property string $daily_profit
 * @property string $referral_earnings
 * @property string $deposit_wallet_address
 * @property string $withdraw_wallet_address
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Transaction[] $transactions
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'email' => true,
        'password' => true,
        'ref_code' => true,
        'referred_by' => true,
        'balance' => true,
        'investment_fund' => true,
        'daily_profit' => true,
        'referral_earnings' => true,
        'deposit_wallet_address' => true,
        'withdraw_wallet_address' => true,
        'created' => true,
        'modified' => true,
        'transactions' => true,
        'investment_days_left' => true,
        'last_quantified' => true,

    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];
}
