<?php


use Phinx\Migration\AbstractMigration;

class AddAccessTokenTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("access_token",     ['id' => false, 'primary_key' => 'id']  );
        $table->addColumn("id",             "uuid"                                  );
        $table->addColumn("user",           "uuid"                                  );
        $table->addColumn("access",         "text"                                  );
        $table->addColumn("notes",          "text"                                  );
        $table->addTimestamps();
        $table->create();

        $this->execute('ALTER TABLE access_token ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE access_token ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");

    }
}
