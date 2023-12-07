<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resptitulado".
 *
 * @property int $id
 * @property int $idEncuesta
 * @property string $CI
 * @property string|null $FechaRespuesta
 * @property string|null $FechaRespuestaEditada
 *
 * @property Titulado $cI
 * @property Encuesta $idEncuesta0
 * @property Respuesta[] $respuestas
 */
class Resptitulado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resptitulado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idEncuesta', 'CI'], 'required'],
            [['idEncuesta'], 'integer'],
            [['FechaRespuesta', 'FechaRespuestaEditada'], 'safe'],
            [['CI'], 'exist', 'skipOnError' => true, 'targetClass' => Titulado::class, 'targetAttribute' => ['CI' => 'CI']],
            [['idEncuesta'], 'exist', 'skipOnError' => true, 'targetClass' => Encuesta::class, 'targetAttribute' => ['idEncuesta' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idEncuesta' => 'Id Encuesta',
            'CI' => 'Ci',
            'FechaRespuesta' => 'Fecha Respuesta',
            'FechaRespuestaEditada' => 'Fecha Respuesta Editada',
        ];
    }

    /**
     * Gets query for [[CI]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCI()
    {
        return $this->hasOne(Titulado::class, ['CI' => 'CI']);
    }

    /**
     * Gets query for [[IdEncuesta0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdEncuesta0()
    {
        return $this->hasOne(Encuesta::class, ['id' => 'idEncuesta']);
    }

    /**
     * Gets query for [[Respuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestas()
    {
        return $this->hasMany(Respuesta::class, ['idRespTitulado' => 'id']);
    }
}
