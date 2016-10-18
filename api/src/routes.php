<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

spl_autoload_register(function ($classname) {
    require(__DIR__ . "/src/classes/" . $classname . ".php");
});

function getDoorData($request, $require_id = false) {
    $req_data = $request->getParsedBody();
    $data = [];
    $required_fields = ["name", "price"];
    $data["id"] = !empty($req_data["id"]) ?  filter_var($req_data["id"], FILTER_SANITIZE_NUMBER_INT) : "";
    $data["name"] = filter_var($req_data["name"], FILTER_SANITIZE_STRING);
    $data["price"] = filter_var($req_data["price"], FILTER_SANITIZE_NUMBER_FLOAT);

    if ($require_id) {
        $required_fields []= "id";
    }
    $missing_fields = [];

    foreach ($required_fields as $required_field){
        if (!array_key_exists($required_field, $data) or empty($data[$required_field])) {
            $missing_fields []= $required_field;
        }
    }
    if (!empty($missing_fields)) {
        return $missing_fields;
    }


    return $data;
}




$app->group('/doors', function () use ($app) {
    /**
     * GET doorsGet
     * Summary:
     * Notes: Returns all doors from the system
     * Output-Formats: [application/json]
     */
    $app->get("", function (Request $request, Response $response, $args) {
        $doors = new DoorMapper($this->db);
        $response = $response->withJSON($doors->getAll());
        return $response;
    });

    /**
     * GET getDoorById
     * Summary: Find door by ID
     * Notes: Returns a single door
     * Output-Formats: [application/json]
     */

    $app->get("/{id}", function (Request $request, Response $response, $args) {
        $doors = new DoorMapper($this->db);
        $result = $doors->getOne($args["id"]);
        if ($result) {
            $response = $response->withJSON($result);
        } else {
            $response = $response->withJSON(["message" => "Door not found"], 404);
        }
        return $response;

    });


    /**
     * POST addDoor
     * Summary: Add a new door to the store
     * Notes:
     * Output-Formats: [application/json]
     */
    $app->post("", function (Request $request, Response $response, $args) {
        $data = getDoorData($request);
        if (array_key_exists(0, $data)){
            return $response->withJSON(["invalid_fields" => $data], 405);
        }
        $mapper = new DoorMapper($this->db);
        $result = $mapper->newRow($data);
        $response = $response->withJSON($result);
        return $response;
    });

    /**
     * PUT updateDoor
     * Summary: Update an existing door
     * Notes:
     * Output-Formats: [application/json]
     */
    $app->put("", function (Request $request, Response $response, $args) {
        $data = getDoorData($request, $require_id=true);
        if (array_key_exists(0, $data)){
            return $response->withJSON(["invalid_fields" => $data], 405);
        }
        $mapper = new DoorMapper($this->db);
        $result = $mapper->updateRow($data);
        if ($result) {
            $response = $response->withJSON(["message" => "OK"]);
        } else {
            if($mapper->getOne($data["id"])){
                $response = $response->withJSON(["message" => "Door not updated, because same data"]);
            } else {
                $response = $response->withJSON(["message" => "Door not found"], 404);
            }
        }
        return $response;
    });


    /**
     * DELETE deleteDoor
     * Summary: Deletes a door
     * Notes:
     * Output-Formats: [application/json]
     */
    $app->delete("/{id}", function (Request $request, Response $response, $args) {
        $doors = new DoorMapper($this->db);
        $result = $doors->remove($args["id"]);
        if ($result) {
            $response = $response->withJSON(["message" => "OK"]);
        } else {
            $response = $response->withJSON(["message" => "Door not found"], 404);
        }

        return $response;
    });

});


