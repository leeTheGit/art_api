<?php


use Phinx\Migration\AbstractMigration;
use src\service\l;


class AddGroupTable extends AbstractMigration
{

    public function change()
    {
        $table = $this->table("groups",             ['id' => false, 'primary_key' => 'id']   );
        $table->addColumn("id",             "uuid"                                                  );
        $table->addColumn("name",           "text"                                                  );
        $table->addColumn("access",         "text"                                                  );
        $table->addColumn("data_access",    "text",        ['null' => 'true']                       );
        $table->addTimestamps();

        $table->addIndex(['name'], [
            'unique' => true,
            'name' => 'idx_group_name']
        );

        $table->create();

        $this->execute('ALTER TABLE groups ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE groups ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");


        $this->execute("INSERT INTO groups (name, access) VALUES ('Arta', 'full')");

    }
}
