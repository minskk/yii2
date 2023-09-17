<?php

use yii\db\Migration;

/**
 * Handles the creation of table `products`.
 */
class m180524_101831_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('products',
            [
                'id' => $this->primaryKey(),
                'categoryId' => $this->integer(),
                'active' => $this->tinyInteger()->notNull()->defaultValue(0),
                'icon' => $this->string(128),
                'title' => $this->string(128),
                'description' => $this->text(),
                'content' => $this->text(),
                'metaKeys' => $this->string(128),
                'metaDescription' => $this->string(),
                'alias' => $this->string(128),
                'price' => $this->float(2),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ]
        );

        $this->createIndex(
            'idx-products-categoryId',
            'products',
            'categoryId'
        );

        $this->addForeignKey(
            'fk-products-categoryId',
            'products',
            'categoryId',
            'categories',
            'id',
            'SET NULL'
        );

        $this->createIndex(
            'idx-products-active',
            'products',
            'active'
        );

        $this->createIndex(
            'idx-products-alias',
            'products',
            'alias'
        );

        $this->createIndex(
            'idx-products-price',
            'products',
            'price'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-products-price',
            'products'
        );

        $this->dropIndex(
            'idx-products-alias',
            'products'
        );

        $this->dropIndex(
            'idx-products-active',
            'products'
        );

        $this->dropForeignKey(
            'fk-products-categoryId',
            'products'
        );

        $this->dropIndex(
            'idx-products-categoryId',
            'products'
        );

        $this->dropTable('products');
    }
}
