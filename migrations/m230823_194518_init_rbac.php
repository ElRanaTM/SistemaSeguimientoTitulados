<?php

use yii\db\Migration;
use yii\rbac\PhpManager;

/**
 * Class m230823_194518_init_rbac
 */
class m230823_194518_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = new PhpManager;//Yii::$app->authManager;
        
        $rule = new \app\rbac\UserTypeRule;
        $auth->add($rule);

        $permissions = [
            'verTitulado' => 'Ver Titulado',
            'editarTitulado' => 'Editar Titulado',
            'editarEmail' => 'Editar Email',
            'editarPassword' => 'Editar Contraseña',
            'verExperiencias' => 'Ver Experiencias',
            'verExperiencia' => 'Ver Experiencia',
            'crearExperiencia' => 'Crear Experiencia',
            'editarExperiencia' => 'Editar Experiencia',
            'borrarExperiencia' => 'Borrar Experiencia',
            'verEstudios' => 'Ver Estudios',
            'verEstudio' => 'Ver Estudio',
            'crearEstudio' => 'Crear Estudio',
            'editarEstudio' => 'Editar Estudio',
            'borrarEstudio' => 'Borrar Estudio',
            'verAreasDesempenio' => 'Ver Áreas de Desempeño',
            'verAreaDesempenio' => 'Ver Área de Desempeño',
            'editarAreaDesempenio' => 'Editar Área de Desempeño',
            'borrarAreaDesempenio' => 'Borrar Área de Desempeño',
            'verComentarios' => 'Ver Comentarios',
            'crearComentario' => 'Crear Comentario',
            'borrarComentario' => 'Borrar Comentario',
            'verDashboard' => 'Ver Dashboard',
            'generarInforme' => 'Generar Informe',
            'descargarGraficos' => 'Descargar Gráficos',
            'verTitulados' => 'Ver Titulados',
            'verEncuestas' => 'Ver Encuestas',
            'verEncuesta' => 'Ver Encuesta',
            'crearEncuesta' => 'Crear Encuesta',
            'editarEncuesta' => 'Editar Encuesta',
            'borrarEncuesta' => 'Borrar Encuesta',
            'verUsuarios' => 'Ver Usuarios',
            'verUsuario' => 'Ver Usuario',
            'editarUsuario' => 'Editar Usuario',
            'borrarUsuario' => 'Borrar Usuario',
        ];

        foreach ($permissions as $name => $description) {
            $permission = $auth->createPermission($name);
            $permission->description = $description;
            $auth->add($permission);
        }

        $roles = [
            'titulado' => 'Titulado',
            'director' => 'Director',
            'admin' => 'Admin',
            'SuperAdmin' => 'SuperAdmin',
        ];

        foreach ($roles as $name => $description) {
            $role = $auth->createRole($name);
            $role->description = $description;
            $auth->add($role);
        }

        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('verTitulado'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('editarTitulado'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('editarEmail'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('editarPassword'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('verExperiencias'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('verExperiencia'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('crearExperiencia'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('editarExperiencia'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('borrarExperiencia'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('verEstudios'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('verEstudio'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('crearEstudio'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('editarEstudio'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('borrarEstudio'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('verAreasDesempenio'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('verAreaDesempenio'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('editarAreaDesempenio'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('borrarAreaDesempenio'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('verComentarios'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('crearComentario'));
        $auth->addChild($auth->getRole('titulado'), $auth->getPermission('borrarComentario'));

        $auth->addChild($auth->getRole('director'), $auth->getPermission('verDashboard'));
        $auth->addChild($auth->getRole('director'), $auth->getPermission('generarInforme'));
        $auth->addChild($auth->getRole('director'), $auth->getPermission('descargarGraficos'));
        $auth->addChild($auth->getRole('director'), $auth->getPermission('verTitulados'));
        $auth->addChild($auth->getRole('director'), $auth->getPermission('verTitulado'));

        $auth->addChild($auth->getRole('admin'), $auth->getRole('director'));
        $auth->addChild($auth->getRole('admin'), $auth->getPermission('verEncuestas'));
        $auth->addChild($auth->getRole('admin'), $auth->getPermission('verEncuesta'));
        $auth->addChild($auth->getRole('admin'), $auth->getPermission('crearEncuesta'));
        $auth->addChild($auth->getRole('admin'), $auth->getPermission('editarEncuesta'));
        $auth->addChild($auth->getRole('admin'), $auth->getPermission('borrarEncuesta'));

        $auth->addChild($auth->getRole('SuperAdmin'), $auth->getRole('titulado'));
        $auth->addChild($auth->getRole('SuperAdmin'), $auth->getRole('admin'));
        $auth->addChild($auth->getRole('SuperAdmin'), $auth->getPermission('verUsuarios'));
        $auth->addChild($auth->getRole('SuperAdmin'), $auth->getPermission('verUsuario'));
        $auth->addChild($auth->getRole('SuperAdmin'), $auth->getPermission('editarUsuario'));
        $auth->addChild($auth->getRole('SuperAdmin'), $auth->getPermission('borrarUsuario'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}
