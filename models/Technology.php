<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "technologies".
 *
 * @property int $id
 * @property string $name
 * @property int $share
 * @property string $icon
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AboutTechnology[] $aboutTechnologies
 * @property About[] $about
 *
 * @package app\models
 */
class Technology extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'technologies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'name',
                    'about'
                ],
                'required'
            ],
            [
                [
                    'share'
                ],
                'integer'
            ],
            [
                [
                    'name',
                    'icon'
                ],
                'string',
                'max' => 128
            ],
            [
                'name',
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
     * List if attributes.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id',
            'name',
            'share',
            'icon',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE][] = 'about';
        $scenarios[self::SCENARIO_UPDATE][] = 'about';

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('technologies', 'Name'),
            'share' => Yii::t('technologies', 'Share'),
            'icon' => Yii::t('app', 'Icon'),
            'about' => Yii::t('about', 'About'),
            'created_at' => Yii::t('app', 'Created date'),
            'updated_at' => Yii::t('app', 'Updated date'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAboutTechnologies()
    {
        return $this->hasMany(AboutTechnology::class, [
            'technologies_id' => 'id'
        ]);
    }

    /**
     * @return ActiveQuery
     */
    public function getAbout()
    {
        return $this->hasMany(About::class, [
            'id' => 'about_id'
        ])->viaTable('about_technologies', [
            'technologies_id' => 'id'
        ]);
    }

    /**
     * @param $about
     *
     * @return void
     */
    public function setAbout($about): void
    {
        $this->about = $about;
    }

    /**
     * Link with about entity after save.
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->linkWithAbout(empty($this->about) ? [] : $this->about);

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Link with about entity.
     *
     * @param array $aboutList
     */
    private function linkWithAbout(array $aboutList): void
    {
        AboutTechnology::deleteAll([
            'technologies_id' => $this->id
        ]);

        foreach ($aboutList as $aboutId) {
            $aboutTechnologies = new AboutTechnology();
            $aboutTechnologies->technologies_id = $this->id;
            $aboutTechnologies->about_id = $aboutId;
            $aboutTechnologies->save();
        }
    }
}
