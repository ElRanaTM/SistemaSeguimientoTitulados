<?php

namespace app\controllers;

use app\models\Carrera;
use app\models\CarreraUser;
use app\models\Encuesta;
use app\models\Estudios;
use app\models\Experiencia;
use app\models\Titulado;
use app\models\User;
use Yii;
use yii\web\Controller;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;

class InformeController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['generar-informe-pdf'],
                'rules' => [
                    [
                        'actions' => ['generar-informe-pdf'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function checkIfAdmin(){
        if (!Yii::$app->user->isGuest){
            if (Yii::$app->user->identity->user_type === 'admin' || Yii::$app->user->identity->user_type === 'director' || Yii::$app->user->identity->user_type === 'SuperAdmin'){
                return true;
            }
            else{
                return false;
            }
        }
    }

    public function checkIfUserIsTitulado(){
        if (Yii::$app->user->identity->user_type === 'titulado') {
            $user = Yii::$app->user->identity;
            if ($user && $user->titulado) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function actionGenerarInformePdf()
    {
        if(!$this->checkIfAdmin()){
            if ($this->checkIfUserIsTitulado()) {
                Yii::$app->session->setFlash('error', 'No puede acceder a esta página.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        $usuariosRegistrados = Yii::$app->user->identity->carreras != null ?
            User::find()
                ->joinWith('titulado')
                ->joinWith('titulado.carreras')
                ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                ->count()
            : User::find()
                ->where(['<>', 'user_type', 'admin'])
                ->andWhere(['<>', 'user_type', 'director'])
                ->andWhere(['<>', 'user_type', 'SuperAdmin'])
                ->count();

        $tituladosRegistrados = Yii::$app->user->identity->carreras != null ?
            Titulado::find()
                ->joinWith('carreras')
                ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                ->count()
            : Titulado::find()
                ->count();

        $tituladosPostGrado = Yii::$app->user->identity->carreras != null ?
            Estudios::find()
                ->joinWith(['titulado', 'titulado.carreras'])
                ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                ->andWhere(['estudios.EstadoActivo' => true])
                ->count()
            : Estudios::find()
                ->where(['EstadoActivo' => true])
                ->count();

        $tituladosTrabajando = Yii::$app->user->identity->carreras != null ?
            Titulado::find()
                ->joinWith('carreras')
                ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                ->andWhere(['EstadoLaboral' => true])
                ->count()
            : Titulado::find()
                ->where(['EstadoLaboral' => true])
                ->count();

        $tituladosSinTrabajo = Yii::$app->user->identity->carreras != null ?
            Titulado::find()
                ->joinWith('carreras')
                ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                ->andWhere(['EstadoLaboral' => false])
                ->count()
            : Titulado::find()
                ->where(['EstadoLaboral' => false])
                ->count();

        $tituladosSinExperiencia = Yii::$app->user->identity->carreras != null ?
            Titulado::find()
                ->leftJoin('experiencia', 'titulado.CI = experiencia.CI')
                ->joinWith('carreras')
                ->where(['experiencia.id' => null])
                ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])
                ->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                ->count()
            : Titulado::find()
                ->leftJoin('experiencia', 'titulado.CI = experiencia.CI')
                ->where(['experiencia.id' => null])
                ->count();


            $titulacionYears = Yii::$app->user->identity->carreras != null ? 
                Carrera::find()
                ->select(['YEAR(FechaTitulacion) as TitulacionYear'])
                ->distinct()
                ->orderBy(['TitulacionYear' => SORT_ASC])
                ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])
                ->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                ->column()
                : Carrera::find()
                ->select(['YEAR(FechaTitulacion) as TitulacionYear'])
                ->distinct()
                ->orderBy(['TitulacionYear' => SORT_ASC])
                ->column();
            
            $gestionTitulacionLabels = [];
            $gestionTitulacionData = [];
            
                foreach ($titulacionYears as $year) {
            
                    $tituladosAntes = Yii::$app->user->identity->carreras == null ? 
                    Carrera::find()->where(['<', 'FechaTitulacion', $year . '-07-01'])->andWhere(['>', 'FechaTitulacion', $year . '-01-01'])->count()
                    : Carrera::find()->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['<', 'FechaTitulacion', $year . '-07-01'])->andWhere(['>', 'FechaTitulacion', $year . '-01-01'])->count();
                    $tituladosDespues = Yii::$app->user->identity->carreras != null ? 
                    Carrera::find()->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['>=', 'FechaTitulacion', $year . '-07-01'])->andWhere(['<', 'FechaTitulacion', $year . '-12-31'])->count()
                    : Carrera::find()->where(['>=', 'FechaTitulacion', $year . '-07-01'])->andWhere(['<', 'FechaTitulacion', $year . '-12-31'])->count();
                    
                    $gestionTitulacionLabels[] = '1/' . $year ;
                    $gestionTitulacionLabels[] = '2/' . $year ;
                    
                    $gestionTitulacionData[] = $tituladosAntes;
                    $gestionTitulacionData[] = $tituladosDespues;
                }
            $labels = $gestionTitulacionLabels;
            $data = $gestionTitulacionData;
                
            $gestionTitulaciones = [];
                
            foreach ($labels as $index => $label) {
                $gestionTitulaciones[$label] = $data[$index];
            }
                

        $tipoEmprendimiento = Yii::$app->user->identity->carreras != null ? 
            Experiencia::find()
                ->joinWith('titulado')
                ->joinWith('titulado.carreras')
                ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                ->andWhere(['Tipo' => 1])
                ->count() 
            : Experiencia::find()
                ->where(['Tipo' => 1])
                ->count();
        $tipoInstitucion = Yii::$app->user->identity->carreras != null ? 
            Experiencia::find()
                ->joinWith('titulado')
                ->joinWith('titulado.carreras')
                ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                ->andWhere(['Tipo' => 0])
                ->count() 
            : Experiencia::find()
                ->where(['Tipo' => 0])
                ->count();

        $sectoresLabels = Experiencia::find()
            ->select(['Sector'])
            ->distinct()
            ->column();

        $sectoresData = implode(',', array_map(function ($sector) {
                return  Yii::$app->user->identity->carreras != null ? 
                Experiencia::find()
                    ->joinWith('titulado')
                    ->joinWith('titulado.carreras')
                    ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                    ->andWhere(['Sector' => $sector])
                    ->count()
                : Experiencia::find()
                    ->where(['Sector' => $sector])
                    ->count();
            }, $sectoresLabels));

        $tipoSectoresLabels = Experiencia::find()
            ->select(['TipoSector'])
            ->distinct()
            ->column();

        $tipoSectoresData = implode(',', array_map(function ($tipoSector) {
                return Yii::$app->user->identity->carreras != null ? 
                Experiencia::find()
                    ->joinWith('titulado')
                    ->joinWith('titulado.carreras')
                    ->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])
                    ->andWhere(['TipoSector' => $tipoSector])
                    ->count()
                : Experiencia::find()
                    ->where(['TipoSector' => $tipoSector])
                    ->count();
            }, $tipoSectoresLabels));

        $EstadoRelacionLaboralCarreraSi = Yii::$app->user->identity->carreras != null ? 
            Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['EstadoRelacionLaboralCarrera' => 1])->count()
            : Experiencia::find()->where(['EstadoRelacionLaboralCarrera' => 1])->count();
        $EstadoRelacionLaboralCarreraNo = Yii::$app->user->identity->carreras != null ? 
            Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['EstadoRelacionLaboralCarrera' => 0])->count()
            : Experiencia::find()->where(['EstadoRelacionLaboralCarrera' => 0])->count();

        $cargosLabels = Yii::$app->user->identity->carreras != null ? 
            Experiencia::find()->select(['Cargo'])->distinct()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->column()
            : Experiencia::find()->select(['Cargo'])->distinct()->column();

        $cargosData = implode(',', array_map(function ($cargo) {
                return Yii::$app->user->identity->carreras != null ? 
                Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['Cargo' => $cargo])->count()
                : Experiencia::find()->where(['Cargo' => $cargo])->count();
            }, $cargosLabels));

        $rangosSalariales = [
                'labels' => ["Menos de Bs. 2362", "Entre Bs. 2362 - Bs. 2999", "Entre Bs. 3000 - Bs. 3999", "Entre Bs. 4000 - Bs. 5999", "Entre Bs. 6000 - Bs. 7999", "Superior a Bs. 8000"],
                'datasets' => [
                    [
                        'data' => [
                            Yii::$app->user->identity->carreras != null ?
                            Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'A'])->count()
                            : Experiencia::find()->where(['RangoSalarial' => 'A'])->count(),
                            Yii::$app->user->identity->carreras != null ?
                            Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'B'])->count()
                            : Experiencia::find()->where(['RangoSalarial' => 'B'])->count(),
                            Yii::$app->user->identity->carreras != null ?
                            Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'C'])->count()
                            : Experiencia::find()->where(['RangoSalarial' => 'C'])->count(),
                            Yii::$app->user->identity->carreras != null ?
                            Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'D'])->count()
                            : Experiencia::find()->where(['RangoSalarial' => 'D'])->count(),
                            Yii::$app->user->identity->carreras != null ?
                            Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'E'])->count()
                            : Experiencia::find()->where(['RangoSalarial' => 'E'])->count(),
                            Yii::$app->user->identity->carreras != null ?
                            Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['RangoSalarial' => 'F'])->count()
                            : Experiencia::find()->where(['RangoSalarial' => 'F'])->count()
                        ],
                    ]
                ]
            ];
        
        $PeriodosDeTiempos = [
            'labels' => ["Medio Tiempo", "Tiempo Completo", "Otro"],
            'datasets' => [
                [
                    'data' => [
                        Yii::$app->user->identity->carreras != null ? 
                        Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['PeriodoTiempo' => 'A'])->count()
                        : Experiencia::find()->where(['PeriodoTiempo' => 'A'])->count(),
                        Yii::$app->user->identity->carreras != null ? 
                        Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['PeriodoTiempo' => 'B'])->count()
                        : Experiencia::find()->where(['PeriodoTiempo' => 'B'])->count(),
                        Yii::$app->user->identity->carreras != null ? 
                        Experiencia::find()->joinWith('titulado')->joinWith('titulado.carreras')->where(['Carrera.codigoCarrera' => Yii::$app->session->get('codigoCarrera')])->andWhere(['Carrera.CodigoSede' => Yii::$app->session->get('codigoSede')])->andWhere(['PeriodoTiempo' => 'C'])->count()
                        : Experiencia::find()->where(['PeriodoTiempo' => 'C'])->count()
                    ],
                ]
            ]
        ];

        $dashboardHtml = $this->renderPartial('_informePdf', [
            'usuariosRegistrados' => $usuariosRegistrados,
            'tituladosRegistrados' => $tituladosRegistrados,
            'tituladosPostGrado' => $tituladosPostGrado,
            'tituladosTrabajando' => $tituladosTrabajando,
            'tituladosSinTrabajo' => $tituladosSinTrabajo,
            'tituladosSinExperiencia' => $tituladosSinExperiencia,
            'gestionTitulaciones' => $gestionTitulaciones,
            'tipoEmprendimiento' => $tipoEmprendimiento,
            'tipoInstitucion' => $tipoInstitucion,
            'sectoresLabels' => $sectoresLabels,
            'sectoresData' => $sectoresData,
            'tipoSectoresLabels' => $tipoSectoresLabels,
            'tipoSectoresData' => $tipoSectoresData,
            'EstadoRelacionLaboralCarreraSi' => $EstadoRelacionLaboralCarreraSi,
            'EstadoRelacionLaboralCarreraNo' => $EstadoRelacionLaboralCarreraNo,
            'cargosLabels' => $cargosLabels,
            'cargosData' => $cargosData,
            'rangosSalariales' => $rangosSalariales,
            'PeriodosDeTiempos' => $PeriodosDeTiempos,
        ]);


        $carreraAdmin = Yii::$app->user->identity->carreras != null ? CarreraUser::getCarreraSedeNonmbres(Yii::$app->session->get('codigoCarrera'),  Yii::$app->session->get('codigoSede')) : 'TODAS LAS CARRERAS';
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $dashboardHtml,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'options' => [
                'title' => 'Informe de Titulados',
            ],
            'methods' => [
                'SetHeader' => ['Informe de Titulados - CARRERA: ' . $carreraAdmin . ' - ' . Yii::$app->formatter->asDate(date('d-m-Y'), 'full'), date('r') ],
                'SetFooter' => [ 'Página {PAGENO}'],
            ],
        ]);

        return $pdf->render();
    }

    public function actionGenerarEncuestaInformePdf($idEncuesta)
    {
        if(!$this->checkIfAdmin()){
            if ($this->checkIfUserIsTitulado()) {
                Yii::$app->session->setFlash('error', 'No puede acceder a esta página.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        
        $modelEncuesta = Encuesta::findOne($idEncuesta);

        $content = $this->renderPartial('_informeEncuestaPdf', ['modelEncuesta' => $modelEncuesta]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'options' => [
                'title' => 'Informe de Encuesta',
            ],
            'methods' => [
                'SetHeader' => ['Informe de Encuesta: ' . $modelEncuesta->TituloEncuesta . ' - ' . Yii::$app->formatter->asDate(date('d-m-Y'), 'full'), date('r')],
                'SetFooter' => ['|Página {PAGENO}|'],
            ],
        ]);

        return $pdf->render();
    }

}
