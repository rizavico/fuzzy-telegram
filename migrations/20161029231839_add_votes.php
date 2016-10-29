<?php

use Phinx\Migration\AbstractMigration;

class AddVotes extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('my_quotes');
        $table->addColumn('votes', 'integer')
              ->update();
    }
}
