<?php
session_start();
include 'vendor/autoload.php';
$username = $_POST['username'] ?? $_SESSION['username'] ?? null;
$password = $_POST['password'] ?? $_SESSION['password'] ?? null;
$estateId = $_GET['id'] ?? null;
$logout = $_GET['logout'] ?? false;

use Whise\Api\WhiseApi;
use Dotenv\Dotenv;
$api = new WhiseApi();

$dotenv = Dotenv::createImmutable(__DIR__,'.env');
$dotenv->load();

$payload = file_get_contents('php://input');
if($logout) {
  unset($_SESSION['username']);
  unset($_SESSION['password']);
  $username = null;
  $password = null;
}

// Retrieve existing access token from storage (getAccessTokenFromDataStore to be implemented)
try {
    $accessToken = $api->getAccessToken();
} catch (\Throwable $th) {
    $accessToken = $api->requestAccessToken($_ENV['WHISE_USER'], $_ENV['WHISE_PWD']);
    $api->setAccessToken($accessToken);
}
$estates = [];
$activities = [];
try {
      $clientToken = $api->requestClientToken(1061,2045);
      $api->setAccessToken($clientToken);
      if($username && $password) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        if($estateId) {
          $activities = $api->activities()->calendars(["EstateId"=> $estateId]);
          $typedActivities = $api->activities()->histories(["EstateId"=> $estateId]);
        } else {
          $estates = $api->estates()->owned()->list($username,$password);
        }
      }
   
} catch (Exception $e) {
    var_dump($e->getMessage());
}

?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./output.css" rel="stylesheet">
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
</head>
<body>
<header class="bg-white">
  <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
    <div class="flex lg:flex-1">
      <a href="#" class="-m-1.5 p-1.5">
        
        <img class="h-8 w-auto" src="https://www.jamproperties.be/wp-content/uploads/2022/02/cropped-logo.png" alt="">
      </a>
    </div>
    
    <div class="hidden lg:flex lg:gap-x-12">
      <?php 
      if($estateId && !empty($_SESSION['username'])): ?>
        <a href="./activities.php" class="text-sm font-semibold leading-6 text-orange-700">Liste de vos biens</a>
     <?php endif;?>
      
    </div>
    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
      <a href="?logout=true" class="text-sm font-semibold leading-6  text-orange-700"><?=(!empty($_SESSION['username'])) ? 'Déconnexion':''?> <span aria-hidden="true">&rarr;</span></a>
    </div>
  </nav>
 
</header>

<?php if(empty($_SESSION['username'])) : ?>
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img class="mx-auto h-10 w-auto" src="https://www.jamproperties.be/wp-content/uploads/2022/02/cropped-logo.png" alt="Your Company">
    <h2 class="mt-10 text-center text-xl font-bold leading-9 tracking-tight text-slate-700">Connectez-vous pour voir l'hitorique des activités de vos biens</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" action="./activities.php" method="POST">
      <div>
        <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Nom d'utilisateur</label>
        <div class="mt-2">
          <input id="username" name="username" type="username" autocomplete="username" required  value="<?=$username?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Mot de passe</label>
          
        </div>
        <div class="mt-2">
          <input id="password" name="password" type="password" autocomplete="current-password" required value="<?=$password?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div>
        <button type="submit" class="flex w-full justify-center rounded-md bg-orange-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Connexion</button>
      </div>
    </form>

  </div>
</div>
<?php else: ?>

<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
<div class="mt-10 sm:mx-auto sm:w-full sm:max-w-2xl">
  
<?php
 

 if($estateId):
  
  if($activities->count()):
    ?>
    <h2 class="font-bold text-xl py-3">Historique</h2>
    <ul role="list" class=" space-y-2 gap-y-2">
   <?php 
   foreach ($typedActivities as $key => $activity): ?>
      <li class="flex justify-between gap-x-6 py-5 border rounded-md border-slate-200 px-2 mt-2">
        <div class="flex min-w-0 gap-x-4">
          
          <div class="min-w-0 flex-auto">
            <p class="text-sm font-semibold leading-7 text-gray-900">#: <?=$activity->categoryId .' '.$activity->category?></p>
            <div class="flex flex-col ">
            <p class="mt-1 truncate text-sm leading-7 text-gray-700 font-bold"><?=$activity->subject ?? $activity->message?></p>
            <p class="mt-1 truncate text-sm leading-7 text-gray-500"><?=$activity->message?></p>
            </div>
          </div>
        </div>
       <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
       <p class="mt-1 text-xs leading-10 text-gray-500">
             <time datetime="<?= $activity->dateTime->format('d-m-Y')?>"><?= $activity->dateTime->format('d-m-Y')?></time>
            </p>
          
        </div>
      </li>
<?php
    endforeach;
    ?>
    </ul>
    <h2 class="font-bold text-xl py-3">Activitées</h2>
    <ul role="list" class=" space-y-2 gap-y-2">
   <?php 
   foreach ($activities as $key => $activity): ?>
      <li class="flex justify-between gap-x-6 py-5 border rounded-md border-slate-200 px-2 mt-2">
        <div class="flex min-w-0 gap-x-4">
          
          <div class="min-w-0 flex-auto">
            <p class="text-sm font-semibold leading-7 text-gray-900">#: <?=$activity->actionTypeId?></p>
            <div class="flex flex-col ">
            <p class="mt-1 truncate text-sm leading-7 text-gray-700 font-bold"><?=$activity->subject?></p>
            <p class="mt-1 truncate text-sm leading-7 text-gray-500"><?=$activity->message?></p>
            </div>
          </div>
        </div>
       <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
       <p class="mt-1 text-xs leading-10 text-gray-500">
             <time datetime="<?= $activity->dateTime->format('d-m-Y')?>"><?= $activity->dateTime->format('d-m-Y')?></time>
            </p>
          
        </div>
      </li>
<?php
    endforeach;
    ?>
    </ul>
    <?php
  endif;
 else:
  if($estates->count()):?>
    <ul role="list" class=" space-y-2 gap-y-2">
   <?php 
   foreach ($estates as $key => $estate): ?>
      <li class="flex justify-between gap-x-6 py-5 border rounded-md border-slate-200 px-2 mt-2">
        <div class="flex min-w-0 gap-x-4">
          <a href="?id=<?=$estate->id?>"><img class="h-16 w-16 flex-none rounded-full bg-gray-50" src="<?=$estate->pictures[0]->urlSmall?>" alt=""></a>
          <a href="?id=<?=$estate->id?>"> <div class="min-w-0 flex-auto">
            <p class="text-sm font-semibold leading-7 text-gray-900">#: <?=$estate->id?></p>
            <p class="mt-1 truncate text-sm leading-7 text-gray-500"><?=$estate->city?></p>
          </div></a>
        </div>
        <a href="?id=<?=$estate->id?>"><div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
          <p class="text-sm leading-6 text-gray-900"><?=$estate->name?></p>
          <p class="mt-1 text-xs leading-10 text-gray-500">
            Mise à jour <time datetime="<?= $estate->updateDateTime->format('d-m-Y')?>"><?= $estate->updateDateTime->format('d-m-Y')?></time>
            </p>
        </div></a>
      </li>
<?php
    endforeach;
    ?>
    </ul>
    <?php
  endif;
endif;
 
?>
</div>
</div>

<?php endif; ?>
</body>
</html>
