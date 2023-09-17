<?php

use yii\db\Migration;

/**
 * Handles the creation of table `positions`.
 */
class m190502_163142_create_positions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('positions',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(64)->notNull(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('positions');
    }
}
