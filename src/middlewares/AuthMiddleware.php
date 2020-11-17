<?php
namespace App\Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Slim\Psr7\Response;
use Clases\Token;

class AuthMiddleware{

    public function __invoke($request, $handler){
        $parsedBody = $request->getParsedBody();
        $token = $request->getHeader('token');
        
        // $user = Token::VerificarToken($token[0]);
        // var_dump($user);
        if(Token::VerificarToken($token[0])){
            $response = new Response();
            //como retornamos el new response se ejecuta antes
            
            
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
            $resp = new Response();
            $resp->getBody()->write($existingContent);
            return $resp;
        }
        else{
            //podria lanzar una excepcion, manejarla de otro lado
            $rta = array("rta"=> "Se encuentra autenticado correctamente");
            $response->getBody()->write(json_encode($rta));
            return $response->withStatus(403); //puedo responder un status o lanzar excepcion
        }

        $response = $handler->handle($request);
        $existingContent = (string) $response->getBody();
        

        return $response;
    }





}


?>