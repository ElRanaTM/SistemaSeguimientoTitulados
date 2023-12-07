<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "respuesta".
 *
 * @property int $id
 * @property string|null $TextoRespuesta
 * @property int $idEncuesta
 * @property int $user_id
 * @property int $idPregunta
 * @property int $idRespTitulado
 * @property string|null $FechaRespuesta
 *
 * @property Encuesta $idEncuesta0
 * @property Pregunta $idPregunta0
 * @property Resptitulado $idRespTitulado0
 * @property User $user
 */
class Respuesta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'respuesta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TextoRespuesta'], 'string'],
            [['idEncuesta', 'user_id', 'idPregunta', 'idRespTitulado'], 'required'],
            [['idEncuesta', 'user_id', 'idPregunta', 'idRespTitulado'], 'integer'],
            [['FechaRespuesta'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['idEncuesta'], 'exist', 'skipOnError' => true, 'targetClass' => Encuesta::class, 'targetAttribute' => ['idEncuesta' => 'id']],
            [['idPregunta'], 'exist', 'skipOnError' => true, 'targetClass' => Pregunta::class, 'targetAttribute' => ['idPregunta' => 'id']],
            [['idRespTitulado'], 'exist', 'skipOnError' => true, 'targetClass' => Resptitulado::class, 'targetAttribute' => ['idRespTitulado' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'TextoRespuesta' => 'Texto Respuesta',
            'idEncuesta' => 'Id Encuesta',
            'user_id' => 'User ID',
            'idPregunta' => 'Id Pregunta',
            'idRespTitulado' => 'Id Resp Titulado',
            'FechaRespuesta' => 'Fecha Respuesta',
        ];
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
     * Gets query for [[IdPregunta0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdPregunta0()
    {
        return $this->hasOne(Pregunta::class, ['id' => 'idPregunta']);
    }

    /**
     * Gets query for [[IdRespTitulado0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdRespTitulado0()
    {
        return $this->hasOne(Resptitulado::class, ['id' => 'idRespTitulado']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
