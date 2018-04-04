<?php


use Phinx\Migration\AbstractMigration;

class AddLogTable extends AbstractMigration
{

    public function change()
    {
        $table = $this->table("log",     ['id' => false, 'primary_key' => 'id']  );
        $table->addColumn("id",             "uuid"                                  );
        $table->addColumn("type",           "text"                                  );
        $table->addColumn("user",           "uuid"                                  );
        $table->addColumn("message",        "text"                                  );
        $table->addColumn("level",          "integer"                               );
        $table->addTimestamps();
        $table->create();

        $this->execute('ALTER TABLE log ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE log ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");

    }
}
