<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddLastQuantifiedToUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
       $table = $this->table('users');

    $table->addColumn('last_quantified', 'date', [
        'null' => true,
        'default' => null,
        'comment' => 'Fecha de última cuantificación'
    ]);

    $table->update();
    }
}
