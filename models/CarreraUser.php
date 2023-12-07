<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "carrera_user".
 *
 * @property int $id
 * @property string|null $NombreCarrera
 * @property int $codigoCarrera
 * @property int|null $user_id
 *
 * @property Users $user
 */
class CarreraUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrera_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigoCarrera'], 'required'],
            [['codigoCarrera', 'user_id', 'CodigoSede'], 'integer'],
            [['NombreCarrera', 'NombreSede'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'NombreCarrera' => 'Nombre Carrera',
            'codigoCarrera' => 'Codigo Carrera',
            'NombreSede' => 'Nombre Sede',
            'CodigoSede' => 'Codigo Sede',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    public static function getCarreraSedeNonmbres($codigoCarrera, $CodigoSede)
    {
        $resultado = self::find()
            ->select(['NombreCarrera', 'NombreSede'])
            ->where(['codigoCarrera' => $codigoCarrera, 'CodigoSede' => $CodigoSede])
            ->one();

        if ($resultado !== null) {
            return $resultado->NombreCarrera . ' - ' . $resultado->NombreSede;
        }

        return null;
    }
}
