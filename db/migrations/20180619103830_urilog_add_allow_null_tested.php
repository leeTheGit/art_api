<?php


use Phinx\Migration\AbstractMigration;

class UrilogAddAllowNullTested extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("urilog");
        $table->changeColumn('tested', 'timestamp', ['null' => 'allow']);
        $table->update();
    }
}
