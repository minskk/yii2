<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contacts".
 *
 * @property int $id
 * @property int $default
 * @property string $title
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property string $metaKeys
 * @property string $metaDescription
 * @property string $created_at
 * @property string $updated_at
 * @property string $mapQ
 * @property int $mapZoom
 *
 * @property ContactSocial[] $contactSocial
 * @property Social[] $social
 *
 * @package app\models
 */
class Contact extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contacts';
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
                    'metaKeys',
                    'metaDescription'
                ],
                'required'
            ],
            [
                [
                    'title',
                    'metaKeys',
                    'address',
                    'email',
                    'phone',
                    'mapQ',
                ],
                'string',
                'max' => 128
            ],
            [
                'metaDescription',
                'string',
                'max' => 255
            ],
            [
                [
                    'default',
                    'mapZoom'
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'default' => Yii::t('app', 'Default'),
            'title' => Yii::t('contacts', 'Title'),
            'address' => Yii::t('contacts', 'Address'),
            'email' => Yii::t('contacts', 'Email'),
            'phone' => Yii::t('contacts', 'Phone'),
            'metaKeys' => Yii::t('app', 'Meta keys'),
            'metaDescription' => Yii::t('app', 'Meta description'),
            'created_at' => Yii::t('app', 'Created date'),
            'updated_at' => Yii::t('app', 'Updated date'),
            'mapQ' => Yii::t('contacts', 'Map place'),
            'mapZoom' => Yii::t('contacts', 'Map zoom'),
        ];
    }

    /**
     * Returns the default contacts record.
     *
     * @return null|\yii\db\ActiveRecord
     */
    public static function getDefaultContacts()
    {
        return static::find()
            ->where([
                'default' => 1
            ])
            ->one();
    }

    /**
     * Reset the default contacts record.
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
     * @return \yii\db\ActiveQuery
     */
    public function getContactSocial()
    {
        return $this->hasMany(ContactSocial::class, [
            'contacts_id' => 'id'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocial()
    {
        return $this->hasMany(Social::class, [
            'id' => 'social_id'
        ])->viaTable('contacts_social', [
            'contacts_id' => 'id'
        ]);
    }
}
