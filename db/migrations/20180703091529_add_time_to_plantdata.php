<?php


use Phinx\Migration\AbstractMigration;

class AddTimeToPlantdata extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("plantdata");
        $table->addColumn("time", "timestamp", ['default'=> '2018-06-05']);
        $table->update();

        $this->execute("ALTER TABLE plantdata ALTER COLUMN time SET DEFAULT timezone('Australia/Melbourne'::text, now())");

    }
}
