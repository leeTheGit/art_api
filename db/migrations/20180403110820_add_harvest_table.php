<?php


use Phinx\Migration\AbstractMigration;

class AddHarvestTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("harvest",                     ['id' => false, 'primary_key' => 'id']   );
        $table->addColumn("id",             "uuid"                                                  );
        $table->addColumn("plant_id",       "uuid"                                                  );
        $table->addColumn("weight",         "float", ['null' => 'true']                              );
        $table->addColumn("staff_id",       "uuid", ['null' => 'true']                              );
        $table->addColumn("staff_check",    "uuid", ['null' => 'true']                              );
        $table->addTimestamps();
        $table->addForeignKey('plant_id', 'plant', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addForeignKey('staff_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addForeignKey('staff_check', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);

        $table->create();

        $this->execute('ALTER TABLE harvest ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE harvest ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");

    }
}
