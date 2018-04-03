<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\PostgresAdapter;

class AddLocationTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("location",      ['id' => false, 'primary_key' => 'id']   );
        $table->addColumn("id",             "uuid"                                      );
        $table->addColumn("name",           "text"                                      );
        $table->addColumn("rank",           "integer", ['null' => 'true']               );
        $table->addTimestamps();
        $table->addIndex(['name'], [
            'unique' => true,
            'name' => 'idx_location_name']
        );

        $table->create();

        $this->execute('ALTER TABLE location ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE location ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");

    }
}
