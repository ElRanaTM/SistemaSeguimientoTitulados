<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "carrera".
 *
 * @property int $id
 * @property string $NombreCarrera
 * @property string $FechaIngreso
 * @property string $FechaEgreso
 * @property string $FechaTitulacion
 * @property string $ModalidadDeTitulacion
 * @property string $CI
 *
 * @property AreaDesempenio[] $areaDesempenios
 * @property Titulado $cI
 */
class Carrera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'NombreCarrera', 'GestionIngreso', 'FechaEgreso', 'FechaTitulacion', 'ModalidadDeTitulacion', 'CI'], 'required'],
            //[['id'], 'integer'],
            [['GestionIngreso', 'FechaEgreso', 'FechaTitulacion'], 'safe'],
            [['NombreCarrera'], 'string', 'max' => 100],
            [['ModalidadDeTitulacion'], 'string', 'max' => 70],
            [['CI'], 'string', 'max' => 10],
            [['id'], 'unique'],
            [['CI'], 'exist', 'skipOnError' => true, 'targetClass' => Titulado::class, 'targetAttribute' => ['CI' => 'CI']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'NombreCarrera' => 'Nombre Carrera',
            'GestionIngreso' => 'Gestion de Ingreso',
            'FechaEgreso' => 'Fecha de Conclución de Estudios',
            'FechaTitulacion' => 'Fecha de Titulacion',
            'ModalidadDeTitulacion' => 'Modalidad De Titulacion',
            'CI' => 'Cédula de Identidad',
        ];
    }

    /**
     * Gets query for [[AreaDesempenios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDesempenios()
    {
        return $this->hasMany(AreaDesempenio::class, ['idCarrera' => 'id']);
    }

    /**
     * Gets query for [[CI]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTitulado()
    {
        return $this->hasOne(Titulado::class, ['CI' => 'CI']);
    }

    public static function getCarrerasNombre()
    {
        return static::find()
            ->select(['NombreCarrera'])
            ->indexBy('NombreCarrera')
            ->column();
    }
}
