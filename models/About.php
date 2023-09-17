<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "about".
 *
 * @property int $id
 * @property int $default
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $metaKeys
 * @property string $metaDescription
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AboutTechnology[] $aboutTechnologies
 * @property Technology[] $technologies
 * @property AboutQuality[] $aboutQualities
 * @property Quality[] $qualities
 *
 * @package app\models
 */
class About extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'about';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'title',
                    'content',
                ],
                'required'
            ],
            [
                [
                    'description',
                    'content',
                ],
                'string'
            ],
            [
                [
                    'title',
                    'metaKeys',
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'metaDescription',
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'default'
                ],
                'integer'
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
                    'updated_at'
                ],
                'safe'
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
            'default',
            'title',
            'description',
            'content',
            'metaKeys',
            'metaDescription',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'default' => Yii::t('app', 'Default'),
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
     * Reset the default about record.
     *
     * @param boolean $insert
     *
     * @return mixed
     */
    public function beforeSave($insert)
    {
        if ($this->default == 1) {

            $default = static::findOne([
                'default' => 1,
            ]);

            if (null !== $default) {
                $default->default = 0;
                $default->save();
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * Returns the default about record.
     *
     * @return null|\yii\db\ActiveRecord
     */
    public static function getDefaultAbout()
    {
        return static::find()
            ->where([
                'default' => 1
            ])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAboutTechnologies()
    {
        return $this->hasMany(AboutTechnology::class, [
            'about_id' => 'id'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTechnologies()
    {
        return $this->hasMany(Technology::class, [
            'id' => 'technologies_id'
        ])->viaTable('about_technologies', [
            'about_id' => 'id'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAboutQualities()
    {
        return $this->hasMany(AboutQuality::class, [
            'about_id' => 'id'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQualities()
    {
        return $this->hasMany(Quality::class, [
            'id' => 'qualities_id'
        ])->viaTable('about_qualities', [
            'about_id' => 'id'
        ]);
    }
}
