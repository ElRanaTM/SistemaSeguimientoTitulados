<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estudios".
 *
 * @property int $id
 * @property int $EstadoActivo
 * @property string $NombreCurso
 * @property string $GradoAcademico
 * @property string $Universidad
 * @property string $FechaActualizacion
 * @property string|null $CI
 *
 * @property Titulado $cI
 */
class Estudios extends \yii\db\ActiveRecord
{

    const EVENT_AFTER_SAVE = 'afterSave';
    const EVENT_AFTER_UPDATE = 'afterUpdate';
    const EVENT_AFTER_DELETE = 'afterDelete';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estudios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['EstadoActivo', 'NombreCurso', 'GradoAcademico', 'Universidad', 'FechaActualizacion'], 'required'],
            //[['id', 'EstadoActivo'], 'integer'],
            [['FechaActualizacion'], 'safe'],
            [['NombreCurso'], 'string', 'max' => 200],
            [['GradoAcademico'], 'string', 'max' => 40],
            [['Universidad'], 'string', 'max' => 100],
            [['CI'], 'string', 'max' => 30],
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
            'EstadoActivo' => 'Estado',
            'NombreCurso' => 'Nombre del Curso',
            'GradoAcademico' => 'Grado Academico',
            'Universidad' => 'Universidad',
            'FechaActualizacion' => 'Fecha de Actualizacion',
            'CI' => 'Cédula de identidad',
        ];
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

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = array_merge($scenarios[self::SCENARIO_DEFAULT], ['FechaActualizacion']);
        return $scenarios;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    
        if ($this->titulado !== null) {
        $this->titulado->updateEstadoFromEstudios();
        }
    }

    public function afterUpdate()
    {
        parent::afterUpdate();

        if ($this->titulado !== null) {
        $this->titulado->updateEstadoFromEstudios();
        }
    }
    
    public function afterDelete()
    {
        parent::afterDelete();
    
        if ($this->titulado !== null) {
        $this->titulado->updateEstadoFromEstudios();
        }
    }    

}
