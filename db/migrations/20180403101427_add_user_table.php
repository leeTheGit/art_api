<?php


use Phinx\Migration\AbstractMigration;
use src\service\l;

class AddUserTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("users",                     ['id' => false, 'primary_key' => 'id']   );
        $table->addColumn("id",             "uuid"                                                  );
        $table->addColumn("firstname",      "text"                                                  );
        $table->addColumn("lastname",       "text"                                                  );
        $table->addColumn("access",         "text"                                                  );
        $table->addColumn("username",       "text"                                                  );
        $table->addColumn("usergroup",      "uuid",         ['null' => 'true']                      );
        $table->addColumn("password",       "text"                                                  );
        $table->addColumn("email",          "text",         ['null' => 'true']                      );
        $table->addColumn("pin",            "integer",      ['null' => 'true']                      );
        $table->addTimestamps();

        $table->addIndex(['username'], [
            'unique' => true,
            'name' => 'idx_username']
        );

        $table->create();

        $this->execute('ALTER TABLE users ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        $this->execute("ALTER TABLE users ALTER COLUMN created_at SET DEFAULT timezone('Australia/Melbourne'::text, now())");
   

        $group = $this->fetchRow("SELECT id from groups WHERE name = 'Arta'");
        $groupid = $group['id'];
        $sql = "INSERT INTO users (firstname, lastname, username, password, usergroup, access)
        VALUES ('bugs', 'bunny', 'bugs', crypt('artadb', gen_salt('bf')), '".$groupid."', 'admin')";
        $this->execute($sql);
    }

}
