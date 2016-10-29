<?php

use Phinx\Migration\AbstractMigration;

class AddLikes extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('my_quotes');
        $table->addColumn('like', 'integer')
              ->update();
    }
}
