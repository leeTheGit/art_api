<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\PostgresAdapter;

class AddPlantdataTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("plantdata",                      ['id' => false, 'primary_key' => 'id']                       );
        $table->addColumn("id",                 "uuid"                                                                       );
        $table->addColumn("plant_id",           "uuid"                                                                       );
        $table->addColumn("height",             "float",        ['null' => 'true']                                           );
        $table->addColumn("location",           "uuid",         ['null' => 'true']                                           );
        $table->addColumn("user_id",            "uuid",         ['null' => 'true']                                           );
        $table->addColumn("notes",              "text",         ['null' => 'true']                                           );
        $table->addColumn("ph",                 "float",        ['null' => 'true']                                           );
        $table->addColumn("conductivity",       "float",        ['null' => 'true']                                           );
        $table->addColumn("temperature",        "float",        ['null' => 'true']                                           );
        $table->addColumn("humidity",           "float",        ['null' => 'true']                                           );
        $table->addColumn("lux",                "float",        ['null' => 'true']                                           );
        $table->addColumn("light_hours",        "float",        ['null' => 'true']                                           );
        $table->addColumn("health",             "integer",      ['null' => 'true', 'limit' => PostgresAdapter::INT_SMALL]    );
        $table->addForeignKey('plant_id', 'plant', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addTimestamps();
        $table->create();

        $this->execute('ALTER TABLE plantdata ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE plantdata ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");
    }
}
