<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users');

        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('ref_code', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('referred_by', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('balance', 'decimal', [
            'default' => 0,
            'null' => false,
            'precision' => 10,
            'scale' => 6,
        ]);
        $table->addColumn('investment_fund', 'decimal', [
            'default' => 0,
            'null' => false,
            'precision' => 10,
            'scale' => 6,
        ]);
        $table->addColumn('daily_profit', 'decimal', [
            'default' => 0,
            'null' => false,
            'precision' => 10,
            'scale' => 6,
        ]);
        $table->addColumn('referral_earnings', 'decimal', [
            'default' => 0,
            'null' => false,
            'precision' => 10,
            'scale' => 6,
        ]);
        $table->addColumn('deposit_wallet_address', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('withdraw_wallet_address', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);

        // Índices
        $table->addIndex(['ref_code'], [
            'name' => 'BY_REF_CODE',
            'unique' => false,
        ]);
        $table->addIndex(['referred_by'], [
            'name' => 'BY_REFERRED_BY',
            'unique' => false,
        ]);
        $table->addIndex(['deposit_wallet_address'], [
            'name' => 'BY_DEPOSIT_WALLET',
            'unique' => false,
        ]);
        $table->addIndex(['withdraw_wallet_address'], [
            'name' => 'BY_WITHDRAW_WALLET',
            'unique' => false,
        ]);

        $table->create();
    }
}
