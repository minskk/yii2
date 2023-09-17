<?php

namespace app\models;

use Yii;
use Itstructure\MultiLevelMenu\MenuWidget;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $metaKeys
 * @property string $metaDescription
 * @property string $created_at
 * @property string $updated_at
 * @property int $parentId
 * @property string $alias
 * @property int $active
 *
 * @package app\models
 */
class Category extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'title',
                    'active',
                    'metaKeys',
                    'metaDescription',
                ],
                'required',
            ],
            [
                [
                    'description',
                    'content',
                ],
                'string',
            ],
            [
                [
                    'icon',
                    'title',
                    'metaKeys',
                    'alias',
                ],
                'string',
                'max' => 128,
            ],
            [
                [
                    'metaDescription',
                ],
                'string',
                'max' => 255,
            ],
            [
                [
                    'parentId',
                    'active'
                ],
                'integer',
            ],
            [
                'parentId',
                'filter',
                'filter' => function ($value) {
                    if (empty($value)) {
                        return null;
                    } else {
                        return MenuWidget::checkNewParentId($this, $value) ? $value : $this->getOldAttribute('parentId');
                    }
                }
            ],
            [
                'alias',
                'filter',
                'filter' => function ($value) {
                    return preg_replace( '/[^a-z0-9_]+/', '-', strtolower(trim($value)));
                }
            ],
            [
                'alias',
                'unique',
                'skipOnError'     => true,
                'targetClass'     => static::class,
                'filter' => $this->getScenario() == self::SCENARIO_UPDATE ? 'id != '.$this->id : ''
            ],
            [
                'title',
                'unique',
                'skipOnError'     => true,
                'targetClass'     => static::class,
                'filter' => $this->getScenario() == self::SCENARIO_UPDATE ? 'id != '.$this->id : ''
            ],
            [
                [
                    'created_at',
                    'updated_at',
                ],
                'safe',
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'id',
            'parentId',
            'alias',
            'active',
            'title',
            'description',
            'content',
            'metaKeys',
            'metaDescription',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parentId' => Yii::t('app', 'Parent object'),
            'active' => Yii::t('app', 'Active status'),
            'alias' => Yii::t('app', 'URL Alias'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'content' => Yii::t('app', 'Content'),
            'metaKeys' => Yii::t('app', 'Meta keys'),
            'metaDescription' => Yii::t('app', 'Meta description'),
            'created_at' => Yii::t('app', 'Created date'),
            'updated_at' => Yii::t('app', 'Updated date'),
        ];
    }

    /**
     * Reassigning child objects to their new parent after delete the main model record.
     */
    public function afterDelete()
    {
        MenuWidget::afterDeleteMainModel($this);

        parent::afterDelete();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getMenu()
    {
        return static::find()->select([
            'id', 'parentId', 'title'
        ])->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getActiveMenu()
    {
        return static::find()->select([
            'id', 'parentId', 'title', 'alias'
        ])->where([
            'active' => 1
        ])->all();
    }
}
