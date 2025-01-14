<?php

namespace DaguConnect;

use Core\Post;
use PDO;

class read{
    private $db;
    private $post;

    public function __construct()
    {
        $this->loadDependencies();

        $this->readData();
    }

    private function loadDependencies():void {
        require_once(__DIR__ . '/../DaguConnect-API_PHP/includes/config.php');
        require_once(__DIR__ . '/../DaguConnect-API_PHP/core/Post.php');
        include_once(__DIR__ . '/../DaguConnect-API_PHP/initialize.php');
    }

    private function readData():void {
        $config = new config();
        $this->db = $config->getDB();

        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        $posts = $this->post = new Post($this->db);

        $result = $posts->read();

        $rowCount = $result->rowCount();


        if ($rowCount > 0) {
            $post_array = array();
            $post_array['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $post_item = array(
                    'id' =>$id,
                    'name' => $name,
                    'created_at' => $created_at
                );
                array_push($post_array['data'], $post_item);
            }
            // covert to json
            echo json_encode($post_array);
        } else {
            echo json_encode(['Message' => 'No available post']);
        }
    }

}
