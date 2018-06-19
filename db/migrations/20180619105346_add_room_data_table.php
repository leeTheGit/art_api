<?php


use Phinx\Migration\AbstractMigration;

class AddRoomDataTable extends AbstractMigration
{

    public function change()
    {
        $table = $this->table("roomdata",               ['id' => false, 'primary_key' => 'id']                  );
        $table->addColumn("id",             "uuid"                                                              );
        $table->addColumn("room_id",        "uuid"                                                              );
        $table->addColumn("temperature",    "integer"                                                           );
        $table->addColumn("humidity",       "integer"                                                           );
        $table->addTimestamps();
        $table->addForeignKey('room_id', 'room', 'id',  ['delete'=> 'CASCADE', 'update'=> 'CASCADE']            );

        $table->create();

        $this->execute('ALTER TABLE roomdata ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE roomdata ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");

    }
}
