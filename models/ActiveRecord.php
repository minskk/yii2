<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Itstructure\AdminModule\interfaces\ModelInterface;

/**
 * Class ActiveRecord
 *
 * @property int $id
 *
 * @package app\models
 */
class ActiveRecord extends \yii\db\ActiveRecord implements ModelInterface
{
    /**
     * Connect behavior to the basic model.
     *
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class'              => TimestampBehavior::class,
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value'              => new Expression('NOW()'),
        ];
        return $behaviors;
    }

    /**
     * Scenarios.
     *
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => $this->attributes(),
            self::SCENARIO_UPDATE => $this->attributes(),
            self::SCENARIO_DEFAULT => $this->attributes(),
        ];
    }

    /**
     * Returns current model id.
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }
}
