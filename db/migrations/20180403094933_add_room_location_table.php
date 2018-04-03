<?php


use Phinx\Migration\AbstractMigration;

class AddRoomLocationTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("roomlocation",                  ['id' => false, 'primary_key' => 'id']                  );
        $table->addColumn("id",             "uuid"                                                                      );
        $table->addColumn("room_id",        "uuid"                                                                      );
        $table->addColumn("location_id",    "uuid"                                                                      );
        $table->addTimestamps();
        $table->addForeignKey('room_id', 'room', 'id',          ['delete'=> 'CASCADE', 'update'=> 'CASCADE']            );
        $table->addForeignKey('location_id', 'location', 'id',  ['delete'=> 'CASCADE', 'update'=> 'CASCADE']            );

        $table->create();

        $this->execute('ALTER TABLE roomlocation ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE roomlocation ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");
    }
}
