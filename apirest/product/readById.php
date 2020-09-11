<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/model.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$model = new Model($db);
 
// set ID property of record to read
$model->iditembuscado = isset($_GET['id']) ? $_GET['id'] : die();
$model->iditembuscado = preg_replace("/[^0-9]/", '', $model->iditembuscado);
 
// read the details of product to be edited
$model->readById();
 
if(count($model->vendings)>0){
    
 
    // set response code - 200 OK
    //http_response_code(200); comentado pois estava bugando no server dedicado
    // make it json format
    echo json_encode($model->vendings);
} 
else{
    // set response code - 404 Not found
    //http_response_code(404); comentado pois estava bugando no server dedicado
 
    // tell the user product does not exist
    echo json_encode(array("erro" => "Nenhum resultado foi encontrado!"));
}

?>