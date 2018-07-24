<?php


use Phinx\Migration\AbstractMigration;

class AddUserCheckToPlantData extends AbstractMigration
{
    public function change()
    {
        $table = $this->table("plantdata");
        $table ->addColumn("user_check", "uuid", ['null' => 'true']);
        // $table->dropForeignKey('tag_id')->save();
        $table->addForeignKey('user_check', 'users', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'CASCADE']);
        $table->addForeignKey('user_id', 'users', 'id', ['delete'=> 'NO_ACTION', 'update'=> 'CASCADE']);
        $table->addForeignKey('location', 'location', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE']);
        $table->update();
    }
}
