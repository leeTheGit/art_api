<?php


use Phinx\Migration\AbstractMigration;

class AddRoomIdToLocations extends AbstractMigration
{

    public function change()
    {
        $table = $this->table("location");
        $table ->addColumn("room_id", "uuid", ['null' => 'true', 'after' => 'rank']);
        $table->addForeignKey('room_id', 'room', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->update();

    }
}
