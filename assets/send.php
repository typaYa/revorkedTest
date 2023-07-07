<?php
use src\RepositoriesReviews;
require __DIR__ . '/../vendor/autoload.php';
require '../config.php';

$RepositoriesReviews  = new RepositoriesReviews("$path");
$text =$_POST['text'];

$RepositoriesReviews->addReview($text);
//header('Location:/../ajax/addReview.php');
echo 'Отзыв отправлен';

?>
