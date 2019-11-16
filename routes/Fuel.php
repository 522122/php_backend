<?php

class Fuel {
    static function listing(&$request, &$response) {
        
        $model = new Model('fuel');
        
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

        $model = new Model('fuel');
    
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

    static function update(&$request, &$response) {

        $model = new Model('fuel');
    
        $updated = $model
            ->model(array(
                "odometer" => $_POST["odometer"],
                "unit_price" => $_POST["unit_price"],
                "quantity" => $_POST["quantity"]
            ))
            ->conditions(array(
                "user" => $request->user["id"],
                "id" => $_GET["id"]
            ))
            ->update();
            
        if (count($updated) > 0) {
            $response->json($updated[0]);
        } else {
            $response->code = 404;
        }
    
    }

    static function insert(&$request, &$response) {

        $model = new Model('fuel');
    
        $inserted = $model
            ->model(array(
                "odometer" => $_POST["odometer"],
                "unit_price" => $_POST["unit_price"],
                "quantity" => $_POST["quantity"],
                "user" => $request->user["id"]
            ))
            ->insert();
    
        if (count($inserted) > 0) {
            $response->json($inserted[0]);
        } else {
            $response->code = 404;
        }
        
    }
}
