<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "qualities".
 *
 * @property int $id
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AboutQuality[] $aboutQualities
 * @property About[] $about
 */
class Quality extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'qualities';
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
                    'description',
                    'icon',
                    'about'
                ],
                'required'
            ],
            [
                [
                    'icon',
                    'title'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'description'
                ],
                'string',
                'max' => 1024
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
     * List if attributes.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id',
            'icon',
            'title',
            'description',
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
            'icon' => Yii::t('app', 'Icon html class'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'about' => Yii::t('about', 'About'),
            'created_at' => Yii::t('app', 'Created date'),
            'updated_at' => Yii::t('app', 'Updated date'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAboutQualities()
    {
        return $this->hasMany(AboutQuality::class, [
            'qualities_id' => 'id'
        ]);
    }

    /**
     * @return ActiveQuery
     */
    public function getAbout()
    {
        return $this->hasMany(About::class, [
            'id' => 'about_id'
        ])->viaTable('about_qualities', [
            'qualities_id' => 'id'
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
     * Returns id of the model.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    protected function linkWithAbout(array $aboutList): void
    {
        AboutQuality::deleteAll([
            'qualities_id' => $this->id
        ]);

        foreach ($aboutList as $aboutId) {
            $aboutQuality = new AboutQuality();
            $aboutQuality->qualities_id = $this->id;
            $aboutQuality->about_id = $aboutId;
            $aboutQuality->save();
        }
    }
}
