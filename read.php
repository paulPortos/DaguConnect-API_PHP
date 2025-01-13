<?php

    header('Allow-Control-Access-Origin: *');
    header('Content-Type: application/json');

    //Initialize the API
    require_once('../DaguConnect-API_PHP/Config/config.php');
    require_once('/../DaguConnect-API_PHP/Post.php');
    include_once('/../DaguConnect-API_PHP/initialize.php');

    $db = getDbConnection();

    $post = new Post($db);

    var_dump($post, $db);