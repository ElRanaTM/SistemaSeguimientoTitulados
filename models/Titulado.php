<?php

namespace app\models;

use Yii;
use yii\bootstrap4\Html;
use zantknight\yii\capture\CamCaptureBehavior;

/**
 * This is the model class for table "titulado".
 *
 * @property string $CI
 * @property string $Nombres
 * @property string $ApPaterno
 * @property string $ApMaterno
 * @property string $Foto
 * @property int $Celular
 * @property int $CodPaisCelular
 * @property string $PaisActual
 * @property string|null $DepartamentoActual
 * @property string|null $CiudadActual
 * @property int $EstadoLaboral
 * @property int $EstadoPostGrado
 * @property string $FechaActualizacion
 *
 * @property Carrera[] $carreras
 * @property EstudiosRealizados[] $estudiosRealizados
 * @property ExperienciaLaboral[] $experienciaLaborals
 * @property User[] $usuarios
 */
class Titulado extends \yii\db\ActiveRecord
{

    const EVENT_AFTER_SAVE = 'afterSave';
    const EVENT_AFTER_UPDATE = 'afterUpdate';
    const EVENT_AFTER_DELETE = 'afterDelete';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'titulado';
    }


    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    
    public function rules()
    {
        return [
            [['CI', 'Nombres', 'ApPaterno', 'ApMaterno', 'Celular', 'CodPaisCelular', 'PaisActual', 'EstadoLaboral', 'EstadoPostGrado', 'FechaActualizacion'], 'required'],
            [['Celular', 'CodPaisCelular', 'EstadoLaboral', 'EstadoPostGrado'], 'integer'],
            [['FechaActualizacion'], 'safe'],
            [['CI'], 'string', 'max' => 30],
            [['Nombres', 'PaisActual', 'CiudadActual'], 'string', 'max' => 30],
            [['ApPaterno', 'ApMaterno', 'DepartamentoActual'], 'string', 'max' => 20],
            [['Foto'], 'safe'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2],
            [['CI'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CI' => 'Cédula de identidad',
            'Nombres' => 'Nombres',
            'ApPaterno' => 'Apellido Paterno',
            'ApMaterno' => 'Apellido Materno',
            'Foto' => 'Foto',
            'imageFile' => 'Elige una Foto',
            'Celular' => 'Celular',
            'CodPaisCelular' => 'Código de Pais',
            'PaisActual' => 'Pais Actual',
            'DepartamentoActual' => 'Departamento',
            'CiudadActual' => 'Ciudad',
            'EstadoLaboral' => 'Estado Laboral',
            'EstadoPostGrado' => 'Estado Post Grado',
            'FechaActualizacion' => 'Fecha Actualizacion',
        ];
    }

    /**
     * Gets query for [[Carreras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarreras()
    {
        return $this->hasMany(Carrera::class, ['CI' => 'CI']);
    }

    /**
     * Gets query for [[Estudios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstudios()
    {
        return $this->hasMany(Estudios::class, ['CI' => 'CI']);
    }

    /**
     * Gets query for [[Experiencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExperiencias()
    {
        return $this->hasMany(Experiencia::class, ['CI' => 'CI']);
    }

    /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getResptitulado()
    {
        return $this->hasOne(Resptitulado::class, ['CI' => 'CI']);
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

        if ($insert) {
            $this->updateEstadoFromEstudios();
            $this->updateEstadoFromExperiencias();
        }
    }

    public function afterUpdate()
    {
        parent::afterUpdate();

        $this->updateEstadoFromEstudios();
        $this->updateEstadoFromExperiencias();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $this->updateEstadoFromEstudios();
        $this->updateEstadoFromExperiencias();
    }


    public function hasOneActiveEstudios()
    {
        return $this->getEstudios()->andWhere(['EstadoActivo' => true])->exists();
    }

    public function hasOneActiveExperiencias()
    {
        return $this->getExperiencias()->andWhere(['EstadoActivo' => true])->exists();
    }

    public function updateEstadoFromEstudios()
    {
        $hasActiveEstudios = $this->hasOneActiveEstudios();

        if ($hasActiveEstudios) {
            $this->EstadoPostGrado = true;
            $this->save(false);
        } else {
            $this->EstadoPostGrado = false;
            $this->save(false);
        }
    }

    public function updateEstadoFromExperiencias()
    {
        $hasActiveExperiencias = $this->hasOneActiveExperiencias();

        if ($hasActiveExperiencias) {
            $this->EstadoLaboral = true;
            $this->save(false);
        } else {
            $this->EstadoLaboral = false;
            $this->save(false);
        }
    }

    public function tieneEncuestasSinResponder()
    {
        $carreras = $this->carreras;

        $codigoCarreras = [];
        $codigoSedes = [];

        foreach ($carreras as $carrera) {
            $codigoCarreras[] = $carrera->CodigoCarrera;
            $codigoSedes[] = $carrera->CodigoSede;
        }

        $sinResponder = Encuesta::find()
            ->where(['CodigoCarrera' => $codigoCarreras, 'CodigoSede' => $codigoSedes])
            ->andWhere(['not in', 'id', Resptitulado::find()->select('idEncuesta')->where(['CI' => $this->CI])])
            ->andWhere(['Estado' => true]) //nuevo 10 nov
            ->count();

        return $sinResponder > 0;
    }

    public function getFechaRespuesta($idEncuesta)
    {
        $resptitulado = Resptitulado::find()
            ->where(['CI' => $this->CI, 'idEncuesta' => $idEncuesta])
            ->one();
        return $resptitulado ? $resptitulado->FechaRespuesta : null;
    }

}
