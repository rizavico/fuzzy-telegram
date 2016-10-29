<?php

use Phinx\Migration\AbstractMigration;

class ChangeVotesToViews extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('my_quotes');
        $table->renameColumn('votes', 'views')
              ->renameColumn('like', 'likes')
              ->update();
    }
}
