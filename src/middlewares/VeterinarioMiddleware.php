<?php
namespace App\Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Slim\Psr7\Response;
use Clases\Token;

class VeterinarioMiddleware{

    public function __invoke($request, $handler){
        
        
        $parsedBody = $request->getParsedBody();
        $token = $request->getHeader('token');
        
        $user = Token::VerificarToken($token[0]);
        // var_dump($user);
        $response = new Response();
        if($user->tipo == "veterinario"){
            
            //podria lanzar una excepcion, manejarla de otro lado
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
            $resp = new Response();
            $resp->getBody()->write($existingContent);
            return $resp;
        }
        else{
            $rta = array("rta"=> "Error, no es veterinario");
            $response->getBody()->write(json_encode($rta));
            return $response->withStatus(403);
        }

        $response = $handler->handle($request);
        $existingContent = (string) $response->getBody();
        

        return $response;
    }


}


?>