<?php

use yii\db\Migration;

/**
 * Handles the creation of table `categories`.
 */
class m180522_101830_create_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('categories',
            [
                'id' => $this->primaryKey(),
                'parentId' => $this->integer(),
                'active' => $this->tinyInteger()->notNull()->defaultValue(0),
                'icon' => $this->string(128),
                'title' => $this->string(128)->notNull(),
                'description' => $this->text(),
                'content' => $this->text(),
                'metaKeys' => $this->string(128),
                'metaDescription' => $this->string(),
                'alias' => $this->string(128),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ]
        );

        $this->createIndex(
            'idx-categories-parentId',
            'categories',
            'parentId'
        );

        $this->createIndex(
            'idx-categories-active',
            'categories',
            'active'
        );

        $this->createIndex(
            'idx-categories-alias',
            'categories',
            'alias'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-categories-parentId',
            'categories'
        );

        $this->dropIndex(
            'idx-categories-active',
            'categories'
        );

        $this->dropIndex(
            'idx-categories-alias',
            'categories'
        );

        $this->dropTable('categories');
    }
}
