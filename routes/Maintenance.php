<?php

class Maintenance {
    static function insert(&$request, &$response) {

        $model = new Model('maintenance');
    
        $inserted = $model
            ->model(array(
                "description" => $_POST["description"],
                "price" => $_POST["price"],
                "user" => $request->user["id"]
            ))
            ->insert();
    
        if (count($inserted) > 0) {
            $response->json($inserted[0]);
        } else {
            $response->code = 404;
        }
        
    }

    static function listing(&$request, &$response) {
        
        $model = new Model('maintenance');
        
        $response->json($model
            ->order(array(
                "id" => "DESC"
            ))
            ->conditions(array(
                "user" => $request->user["id"]
            )
            )->select()
        );
    }

    static function delete(&$request, &$response) {

        $model = new Model('maintenance');
    
        $removed = $model
            ->conditions(array(
                "id" => $_GET["id"],
                "user" => $request->user["id"]
            ))
            ->delete();
    
        if (count($removed) > 0) {
            $response->json($removed[0]);
        } else {
            $response->code = 404;
        }
    
    }
}