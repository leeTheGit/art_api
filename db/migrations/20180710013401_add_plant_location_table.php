<?php


use Phinx\Migration\AbstractMigration;

class AddPlantLocationTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("plantlocation",                  ['id' => false, 'primary_key' => 'id']                  );
        $table->addColumn("id",             "uuid"                                                                      );
        $table->addColumn("plant_id",       "uuid"                                                                      );
        $table->addColumn("location_id",    "uuid"                                                                      );
        $table->addForeignKey('plant_id',    'plant',    'id',  ['delete'=> 'CASCADE', 'update'=> 'CASCADE']            );
        $table->addForeignKey('location_id', 'location', 'id',  ['delete'=> 'CASCADE', 'update'=> 'CASCADE']            );
        $table->addTimestamps();
        
        $table->create();

        $this->execute('ALTER TABLE plantlocation ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE plantlocation ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");
    }
}
