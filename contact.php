<?php
header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Headers: Id, Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, Authorization, Session, Accept-Encoding, X-CSRF-Token,cache-control");
// http://api.whise.eu/WebsiteMedia.html
include 'vendor/autoload.php';

use Whise\Api\WhiseApi;
use Dotenv\Dotenv;

$api = new WhiseApi();

$dotenv = Dotenv::createImmutable(__DIR__,'.env');
$dotenv->load();

$payload = file_get_contents('php://input');
$params = null;
if($payload) {
    $params = json_decode($payload,true);
}
// Retrieve existing access token from storage (getAccessTokenFromDataStore to be implemented)
try {
    $accessToken = $api->getAccessToken();
} catch (\Throwable $th) {
    $accessToken = $api->requestAccessToken($_ENV['USER'], $_ENV['PWD']);
    $api->setAccessToken($accessToken);
}
try {
    if($params) {
        $clientToken = $api->requestClientToken(1061,2045);
        $api->setAccessToken($clientToken);
        $reponse = $api->contacts()->create($params);
        echo json_encode(["data"=>$reponse, "message"=>'message sent']);exit;
    } else {
        var_dump("Error params not provided");
    }
} catch (Exception $e) {
    var_dump($e->getMessage());
}
?>
