<?php
namespace views;

class JsonResponse {
    public static function output($data) {
        $ajaxObj = array(
            "success" => true,
            "data" => $data
        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($ajaxObj);
    }

    
    public static function error($message) {        
        $ajaxObj = array(
            "success" => false,
            "message" => $message
        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($ajaxObj);
    }
}