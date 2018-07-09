<?php


use Phinx\Migration\AbstractMigration;

class AddTimeToRoomdata extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("roomdata");
        $table->addColumn("time", "timestamp", ['default'=> '2018-06-05']);
        $table->update();

        $this->execute("ALTER TABLE roomdata ALTER COLUMN time SET DEFAULT timezone('Australia/Melbourne'::text, now())");

    }
}
