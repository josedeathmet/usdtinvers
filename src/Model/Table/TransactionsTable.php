<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class TransactionsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->belongsTo('Users');
        
    $this->setTable('transactions');
    $this->setPrimaryKey('id');

    $this->addBehavior('Timestamp'); 
    }
}
