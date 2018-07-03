<?php


use Phinx\Migration\AbstractMigration;

class AddDeletedTable extends AbstractMigration
{
    public function change()
    {
        // remove the auto generated id column
        //               ↑
        $table = $this->table("deleted",                ['id' => false, 'primary_key' => 'id']  );
        $table->addColumn("id",             "uuid"                                                                      );
        $table->addColumn("tablename",      "text"                                                                      );
        $table->addColumn("content",        "jsonb"                             );
        $table->addColumn("reason",         "text",     ['null' => 'true']      );
        $table->addColumn("user_id",        "uuid"                              );
        $table->addColumn("manager_id",     "uuid",     ['null' => 'true']      );
        $table->addTimestamps();
        $table->addForeignKey('user_id',    'users', 'id',  ['delete'=> 'CASCADE', 'update'=> 'CASCADE'] );
        $table->addForeignKey('manager_id', 'users', 'id',  ['delete'=> 'CASCADE', 'update'=> 'CASCADE'] );

        $table->create();

        $this->execute('ALTER TABLE deleted ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE deleted ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");
    }
}
