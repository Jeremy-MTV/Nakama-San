<?php
if ( empty(session_id()) ) session_start();

function verifSouvenirDeMoi() {
  if (isset($_COOKIE['rester_connecte'])) {
    $co = explode("-----", $_COOKIE['rester_connecte']);
    require_once 'database.php';
    global $db;
    $q = $db->prepare('SELECT * FROM users WHERE id = :id');
    $q->execute(['id' => $co[0]]);
    $res = $q->fetch();  
    $verif = sha1($res["pseudo"] . $res["password"]);
    if ($verif == $co[1]) {
      if (!isset($_SESSION['auth'])) {
        $_SESSION['auth'] = $res;
      }
      return true;
  }
}
  return false;
}
// PHP qui sert à savoir si on a coché la case souvenir de moi

function estConnecte() {
  if (verifSouvenirDeMoi() == true || isset($_SESSION['auth'])) {
    return true;
  }
  return false;
}


// PHP qui sert à savoir si on est connecté ou non





?>
