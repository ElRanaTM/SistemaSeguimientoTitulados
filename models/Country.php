<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property int $id
 * @property int $number
 * @property string $alpha
 * @property int $calling
 * @property string $name_en
 * @property string $name_ru
 * @property string $name_uk
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'alpha', 'calling', 'name_en', 'name_ru', 'name_uk'], 'required'],
            [['number', 'calling'], 'integer'],
            [['alpha'], 'string', 'max' => 2],
            [['name_en', 'name_ru', 'name_uk'], 'string', 'max' => 255],
            [['alpha'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'alpha' => 'Alpha',
            'calling' => 'Calling',
            'name_en' => 'Name En',
            'name_ru' => 'Name Ru',
            'name_uk' => 'Name Uk',
        ];
    }

    /**
     * Devuelve un array asociativo de códigos de llamada y sus nombres.
     * @return array
     */
    public static function getCallingCodes()
    {
        return static::find()
            ->select(['calling', 'name_en'])
            ->orderBy(['name_en' => SORT_ASC])
            ->indexBy('calling')
            ->column();
    }

    /**
     * Devuelve un array asociativo de nombres de países y sus códigos de llamada.
     * @return array
     */
    public static function getCountryNames()
    {
        return static::find()
            ->select(['name_en', 'calling'])
            ->orderBy(['name_en' => SORT_ASC])
            ->indexBy('name_en')
            ->column();
    }

    public static function getCountryCodes()
    {
        return static::find()
            ->select(['alpha', 'name_en', 'calling'])
            ->orderBy(['name_en' => SORT_ASC])
            ->indexBy('calling')
            ->column();
    }

    public static function findAlphaByCalling($calling)
    {
        $country = static::findOne(['calling' => $calling]);

        if ($country !== null) {
            return $country->alpha;
        }

        return null;
    }
    
}
