<?php


use Phinx\Migration\AbstractMigration;

class AddMortalityTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("mortality",                     ['id' => false, 'primary_key' => 'id']   );
        $table->addColumn("id",             "uuid"                                                  );
        $table->addColumn("plant_id",       "uuid"                                                  );
        $table->addColumn("cause",          "text", ['null' => 'true']                              );
        $table->addColumn("notes",          "text", ['null' => 'true']                              );
        $table->addColumn("staff_id",       "uuid", ['null' => 'true']                              );
        $table->addColumn("manager_id",     "uuid", ['null' => 'true']                              );
        $table->addColumn("certificate",    "text", ['null' => 'true']                              );
        $table->addTimestamps();
        $table->addForeignKey('plant_id', 'plant', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addForeignKey('staff_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addForeignKey('manager_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);

        $table->create();

        $this->execute('ALTER TABLE mortality ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE mortality ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");
    }
}
