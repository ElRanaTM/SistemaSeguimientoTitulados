<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "conocimientos".
 *
 * @property string $Descripcion
 * @property string $FechaActualizacion
 * @property int $idAreaDesempenio
 *
 * @property Area $idAreaDesempenio0
 */
class Conocimientos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conocimientos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Descripcion', 'FechaActualizacion', 'idAreaDesempenio'], 'required'],
            [['Descripcion'], 'string'],
            [['FechaActualizacion'], 'safe'],
            [['idAreaDesempenio'], 'integer'],
            [['idAreaDesempenio'], 'exist', 'skipOnError' => true, 'targetClass' => Area::class, 'targetAttribute' => ['idAreaDesempenio' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Descripcion' => 'Descripcion',
            'FechaActualizacion' => 'Fecha Actualizacion',
            'idAreaDesempenio' => 'Id Area Desempenio',
        ];
    }

    /**
     * Gets query for [[IdAreaDesempenio0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdAreaDesempenio0()
    {
        return $this->hasOne(Area::class, ['id' => 'idAreaDesempenio']);
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}