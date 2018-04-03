<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\PostgresAdapter;

class AddPlantTable extends AbstractMigration
{
    public function change()
    {   
                                // remove the auto generated id column
                                //               ↑
        $table = $this->table("plant",                      ['id' => false, 'primary_key' => 'id']                      );
        $table->addColumn("id",             "uuid"                                                                      );
        $table->addColumn("serial",         "text"                                                                      );
        $table->addColumn("mortality",      "integer",      ['null' => 'true', 'limit' => PostgresAdapter::INT_SMALL]   );
        $table->addColumn("lifecycle",      "integer",      ['null' => 'true', 'limit' => PostgresAdapter::INT_SMALL]   );
        $table->addColumn("manager_id",     "uuid",         ['null' => 'true']                                          );
        $table->addTimestamps();

        $table->addIndex(['serial'], [
            'unique' => true,
            'name' => 'idx_plant_serial']
        );

        $table->create();

        $this->execute('ALTER TABLE plant ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE plant ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");
    }
}

