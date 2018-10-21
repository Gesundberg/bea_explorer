<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
require './config.php';

$app = new \Slim\App;

$app->get('/bea', function (Request $request, Response $response, array $args) {
    $response = $response->withHeader('Content-Type', 'text/html');
    $response = $response->withHeader('Content-Length', filesize('./macro_bea.html'));
        
    $newStream = new \GuzzleHttp\Psr7\LazyOpenStream( './macro_bea.html', 'r');
    $response = $response->withBody($newStream);

    return $response;
});

$app->get('/data/{method}', function (Request $request, Response $response, array $args) {
    $method = $args['method'];
    
    $bea = new \Finfor\Helpers\BeaApiClass(BEA_URL, BEA_KEY, $method);
    $param_str = $request->getUri()->getQuery();
    parse_str($param_str, $params);
    $bea->formData($params);
    $json_pre = $bea->getJson();
    
    
    
    $response = $response->withHeader('Access-Control-Allow-Origin','*');
    $response = $response->withJson($json_pre, 200);
    //$response->getBody()->write(var_dump($json_pre));
    return $response;
});


$app->get('/files/{file}', function (Request $request, Response $response, array $args) {
    $file = $args['file'];
    $path = './files/'.$file;
    if(file_exists($path)){
        $file_size = filesize($path);
        
        $type ='';
        if(preg_match('/(\w*)\.(js|css|html)$/is',$file, $poc)){
            $type = $poc[2];
        }
        $content_type = '';
        if($type=='js'){
            $content_type = 'application/javascript';
        }
        if($type=='css'){
            $content_type = 'text/css';
        }
        if($type=='html'){
            $content_type = 'text/html';
        }
        
        $response = $response->withHeader('Content-Type', $content_type);
        $response = $response->withHeader('Content-Length', $file_size);
        
        $newStream = new \GuzzleHttp\Psr7\LazyOpenStream( $path, 'r');
        $response = $response->withBody($newStream);
        return $response;

        
    }else{
        $response->getBody()->write("No file!!");
        return $response;
    }
});




$app->run();

