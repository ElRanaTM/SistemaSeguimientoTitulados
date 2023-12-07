<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pregunta".
 *
 * @property int $id
 * @property int $idEncuesta
 * @property string|null $TextoPregunta
 * @property string|null $TipoPregunta
 *
 * @property Encuesta $idEncuesta0
 * @property Opciones[] $opciones
 * @property Respuesta[] $respuestas
 */
class Pregunta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pregunta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idEncuesta'], 'required'],
            [['idEncuesta'], 'integer'],
            [['TextoPregunta'], 'string'],
            [['TipoPregunta'], 'string', 'max' => 1],
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
            'TextoPregunta' => 'Pregunta',
            'TipoPregunta' => 'Tipo de Pregunta',
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
     * Gets query for [[Opciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpciones()
    {
        return $this->hasMany(Opciones::class, ['idPregunta' => 'id']);
    }

    /**
     * Gets query for [[Respuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespuestas()
    {
        return $this->hasMany(Respuesta::class, ['idPregunta' => 'id']);
    }

    /**
     * Devuelve un array de opciones asociadas a esta pregunta.
     * @return array
     */
    public function getOpcionesArray()
    {
        $opcionesArray = [];
        foreach ($this->opciones as $opcion) {
            $opcionesArray[$opcion->id] = $opcion->Opcion;
        }
        return $opcionesArray;
    }

}
