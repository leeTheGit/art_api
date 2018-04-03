<?php


use Phinx\Migration\AbstractMigration;

class AddLifecycleTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("lifecycle",      ['id' => false, 'primary_key' => 'id']                      );
        $table->addColumn("id",             "uuid"                                                                      );
        $table->addColumn("name",           "text"                                                                      );
        $table->addTimestamps();
        $table->addIndex(['name'], [
            'unique' => true,
            'name' => 'idx_life_name']
        );

        $table->create();

        $this->execute('ALTER TABLE lifecycle ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE lifecycle ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");

    }
}
