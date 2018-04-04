<?php


use Phinx\Migration\AbstractMigration;

class AddUrilogTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("urilog",     ['id' => false, 'primary_key' => 'id']  );
        $table->addColumn("id",             "uuid"                                  );
        $table->addColumn("uri",            "text"                                  );
        $table->addColumn("tested",         "timestamp"                             );
        $table->addColumn("count",          "integer"                               );
        $table->addTimestamps();
        $table->create();

        $this->execute('ALTER TABLE urilog ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE urilog ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");

    }
}
