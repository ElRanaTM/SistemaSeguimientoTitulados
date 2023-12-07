<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "encuesta".
 *
 * @property int $id
 * @property string $TituloEncuesta
 * @property string|null $Descripcion
 * @property string|null $FechaInicio
 * @property string|null $FechaFin
 * @property int|null $Estado
 * @property string|null $FechaCreacion
 * @property string|null $FechaEdicion
 * @property int $user_id
 *
 * @property Pregunta[] $preguntas
 * @property Resptitulado[] $resptitulados
 * @property Respuesta[] $respuestas
 * @property Users $user
 */
class Encuesta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'encuesta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TituloEncuesta'], 'required'],
            [['Descripcion'], 'string'],
            [['FechaInicio', 'FechaFin', 'FechaCreacion', 'FechaEdicion'], 'safe'],
            [['Estado', 'user_id', 'CodigoCarrera'], 'integer'],
            [['CodigoSede'], 'string', 'max' => 2],
            [['TituloEncuesta'], 'string', 'max' => 150],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'TituloEncuesta' => 'Titulo de la Encuesta',
            'Descripcion' => 'Descripción',
            'FechaInicio' => 'Fecha de Inicio',
            'FechaFin' => 'Fecha de Finalización',
            'Estado' => 'Estado de la Encuesta',
            'FechaCreacion' => 'Fecha de Creación',
            'FechaEdicion' => 'Fecha de Edición',
            'CodigoCarrera' => 'Código de la Carrera',
            'CodigoSede' => 'Código de la Sede',
            //'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Preguntas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreguntas()
    {
        return $this->hasMany(Pregunta::class, ['idEncuesta' => 'id']);
    }

    /**
     * Gets query for [[Resptitulados]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResptitulados()
    {
        return $this->hasMany(Resptitulado::class, ['idEncuesta' => 'id']);
    }

    /**
     * Gets query for [[Respuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestas()
    {
        return $this->hasMany(Respuesta::class, ['idEncuesta' => 'id']);
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

    public function getUserResponses($userId)
    {
        $respuestas = Respuesta::find()
            ->where(['idEncuesta' => $this->id, 'user_id' => $userId])
            ->all();

        if (!empty($respuestas)) {
            return $respuestas;
        } else {
            return null;
        }
    }

    public function usuarioHaRespondido()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        if (Yii::$app->user->identity->user_type === 'titulado') {
            return Resptitulado::find()
                ->where(['idEncuesta' => $this->id, 'CI' => Yii::$app->user->identity->titulado->CI])
                ->exists();
        }

        return false;
    }

    public function getTituladosQueHanRespondido()
    {
        $tituladosIds = Resptitulado::find()->select('CI')->where(['idEncuesta' => $this->id])->column();
        return Titulado::find()->where(['CI' => $tituladosIds])->all();
    }

}
