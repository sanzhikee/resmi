<?php

class m170522_121317_phones extends CDbMigration
{
	public function up()
	{
	    $this->createTable('phones', [
	        'id' => 'pk',
            'name' => 'string NOT NULL',
            'phone' => 'string',
            'create_date' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ]);
	}

	public function down()
	{
		$this->dropTable('phones');
		return true;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}