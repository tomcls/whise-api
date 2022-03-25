<?php
header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Headers: Id, Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, Authorization, Session, Accept-Encoding, X-CSRF-Token,cache-control");


// Retrieve existing access token from storage (getAccessTokenFromDataStore to be implemented)
try {
    $json = file_get_contents('https://s3-eu-west-1.amazonaws.com/itcl/jam/web/locationsSale.json'); 
    echo $json; exit;
} catch (Exception $e) {
    echo json_encode(["data"=>$e->getMessage(), "message"=>'error']);exit;
}

?>
