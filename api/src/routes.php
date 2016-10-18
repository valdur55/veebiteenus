<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

spl_autoload_register(function ($classname) {
    require(__DIR__ . "/src/classes/" . $classname . ".php");
});

function getDoorData($request) {
    $req_data = $request->getParsedBody();
    $data = [];
    $data["id"] = !empty($req_data["id"]) ?  filter_var($req_data["id"], FILTER_SANITIZE_NUMBER_INT) : "";
    $data["name"] = filter_var($req_data["name"], FILTER_SANITIZE_STRING);
    $data["price"] = filter_var($req_data["price"], FILTER_SANITIZE_NUMBER_FLOAT);
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
            $response = $response->withStatus(404);
            $response = $response->withJSON([]);

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
        $data = getDoorData($request);
        $mapper = new DoorMapper($this->db);
        $result = $mapper->updateRow($data);
        $response = $response->withJSON($result);
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
        $response = $response->withJSON($result);
        return $response;
    });

});


