<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as Psr7Response;
use src\RepositoriesReviews;


require 'config.php';
require __DIR__ . '/vendor/autoload.php';


$app = AppFactory::create();
$RepositoriesReviews = new RepositoriesReviews("$path");


$templateDir = __DIR__ . '/views';


$loader = new Twig\Loader\FilesystemLoader($templateDir);
$twig = new Twig\Environment($loader);


$basicAuthMiddleware = function (Request $request, RequestHandlerInterface $handler) use ($validUsers) {
    $response = new Psr7Response();
    $authHeader = $request->getHeaderLine('Authorization');
    if (empty($authHeader) || !preg_match('/Basic (.+)/', $authHeader, $matches)) {
        $response->getBody()->write('Authorization required');
        return $response->withStatus(401)->withHeader('WWW-Authenticate', 'Basic realm="My Realm"');
    }
    list($username, $password) = explode(':', base64_decode($matches[1]));
    if (!isset($validUsers[$username]) || $validUsers[$username] !== $password) {
        $response->getBody()->write('Invalid credentials');
        return $response->withStatus(403);
    }
    return $handler->handle($request);
};

$app->map(['GET','POST'],'/api/feedbacks/addReview', function ($request, $response, $args) use ($twig) {

    $template = $twig->load('addReview.twig');
    $html = $template->render();
    $response->getBody()->write($html);
    return $response;
});

$app->get('/api/feedbacks', function (Request $request, Response $response, $args) use ($RepositoriesReviews) {
    $data = $request->getQueryParams();
    $page = $data['page'];
    $perPage = 20;
    $reviews = $RepositoriesReviews->getReviewsByPage($page, $perPage);
    $response->getBody()->write(json_encode($reviews));
    return $response;
});

$app->get('/api/feedbacks/delete/{id}',function (Request $request, Response $response,$args) use ($RepositoriesReviews){
    $id = $args['id'];
    $RepositoriesReviews->deleteReviewById($id);
    $response->getBody()->write("Удалено");
    return $response;
})->add($basicAuthMiddleware);
$app->get('/api/feedbacks/{id}', function (Request $request, Response $response, $args) use ($RepositoriesReviews) {
    $id = $args['id'];
    $review = $RepositoriesReviews->SearchReview($id);
    $response->getBody()->write(json_encode($review));
    return $response;
});
$app->run();
