<?php


use Phinx\Migration\AbstractMigration;

class AddLocationToPlant extends AbstractMigration
{

    
    public function change()
    {
        $table = $this->table("plant");
        $table ->addColumn("location", "uuid", ['null' => 'true', 'after' => 'lifecycle']);
        $table->addForeignKey('location', 'location', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->update();
    }
}
