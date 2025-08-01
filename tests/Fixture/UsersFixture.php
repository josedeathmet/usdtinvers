<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'ref_code' => 'Lorem ipsum dolor sit amet',
                'referred_by' => 'Lorem ipsum dolor sit amet',
                'balance' => 1.5,
                'investment_fund' => 1.5,
                'daily_profit' => 1.5,
                'referral_earnings' => 1.5,
                'deposit_wallet_address' => 'Lorem ipsum dolor sit amet',
                'withdraw_wallet_address' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-06-24 15:41:56',
                'modified' => '2025-06-24 15:41:56',
            ],
        ];
        parent::init();
    }
}
