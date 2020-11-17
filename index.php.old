<?php
require __DIR__ . '/vendor/autoload.php';
use Clases\Profesor;
use Clases\Materias;
use Clases\Token;
use Clases\Usuario;
use Clases\Materia;
use Config\Database;
use \Firebase\JWT\JWT;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use Slim\Exception\HttpNotFoundException;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteCollectorProxy;
use Slim\Middleware\ErrorMiddleware;
use Slim\Exception\NotFoundException;

use App\Controllers\UserController;
use App\Controllers\MateriaController;
use App\Controllers\TurnoController;

use App\Middlewares\JsonMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\UserMiddleware;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\ProfesorMiddleware;
use App\Middlewares\AlumnoMiddleware;
use App\Middlewares\AdminProfesorMiddleware;


session_start();

$app = AppFactory::create();
$app->setBasePath("/SP_Programacion_III");
$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

//para cuando utilice el token
// $headers = $request->getHeaders();
// $headerValueArray = $request->getHeader('Accept');



new Database();





$app->group('', function (RouteCollectorProxy $group) {
    $group->post('/users', UserController::class . ":registro");
    $group->post('/login', UserController::class . ":login");

    $group->group('', function(RouteCollectorProxy $groupUser) {
        $groupUser->post('/materia', MateriaController::class . ":altaMateria")->add(new AdminMiddleware);
        $groupUser->post('/inscripcion/{idMateria}', MateriaController::class . ":cargarInscripcion")->add(new AlumnoMiddleware);
        $groupUser->put('/notas/{idMateria}', MateriaController::class . ":cargarNotas")->add(new ProfesorMiddleware);
        $groupUser->get('/inscripcion/{idMateria}', MateriaController::class . ":MostrarInscriptos")->add(new AdminProfesorMiddleware);
        $groupUser->get('/materia', MateriaController::class . ":MostrarMaterias");
        $groupUser->get('/notas/{idMateria}', MateriaController::class . ":mostrarTodasLasNotas");
        
        // $groupUser->group('/turnos', function(RouteCollectorProxy $groupTurnos) {
        //     $groupTurnos->post('/mascota', TurnoController::class . ":altaTurno");
        //     $groupTurnos->get('/{idUsuario}', TurnoController::class . ":getDatosTurno")->add(new VeterinarioMiddleware);
        //     // $groupTurnos->get('/{id_usuario}', TurnoController::class . ":getDatosCliente");

        // })->add(new UserMiddleware);
        
    })->add(new UserMiddleware)->add(new AuthMiddleware);

    //notas/{idMateria} (GET): Muestra todas las notas de la materia indicada.
    

    // $group->get('/getStats[/{tipo}]', UserController::class . ":getStats")->add(new AdminMiddleware);

    // $group->get('/getAllUsers', UserController::class . ":getAllUsers");
    // $group->get('/getAllVehiculos', UserController::class . ":getAllVehiculos");
    // $group->get('/getServicios/{id}', UserController::class . ":getServicios");

    

    
    $group->put('/{id}', UserController::class . ":update");

    $group->delete('/{id}', UserController::class . ":delete");
});
// ->add(new UserMiddleware)->add(new AuthMiddleware); //primero se ejecuta el ultimo, si no da ok el auth no se ejecuta el userMiddleware
$app->add(new JsonMiddleware);
// ->add(function ($request, $handler) {
//     $response = $handler->handle($request);
//     $response->getBody()->write('AFTER');
//     return $response;
// });



// $app->post('/materia', UserController::class . ":Materia");


$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->run();
?>