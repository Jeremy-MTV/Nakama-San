<?php
session_start();
require_once 'database.php';
require_once 'verifConnexion.php';
if (!estConnecte()) {
    header('Location: connexion.php');
    exit();
} 

// PHP qui permet de pouvoir supprimer chaque éléments du site

if (isset($_GET['commentaire'])) {

    $q = $db->prepare('SELECT * FROM commentaires WHERE commentaire_id=:commentaire_id');
    $q->execute(['commentaire_id' => htmlspecialchars($_GET['commentaire'])]);
    $res = $q->fetch();
    if ($res['user_id'] != $_SESSION['auth']['id'] && $_SESSION['auth']['admin'] == 0) {
        header('Location: '. $_SERVER['HTTP_REFERER']);
        exit();
    }

    $q = $db->prepare('DELETE FROM commentaires WHERE commentaire_id=:commentaire_id');
    $q->execute(['commentaire_id' => htmlspecialchars($_GET['commentaire'])]);
    header('Location:' .$_SERVER['HTTP_REFERER']);

}

if (isset($_GET['commentaireMangas'])) {

    $q = $db->prepare('SELECT * FROM commentaires_mangas WHERE commentaire_id=:commentaire_id');
    $q->execute(['commentaire_id' => htmlspecialchars($_GET['commentaireMangas'])]);
    $res = $q->fetch();
    if ($res['user_id'] != $_SESSION['auth']['id'] && $_SESSION['auth']['admin'] == 0) {
        header('Location: '.$_SERVER['HTTP_REFERER']);
        exit();
    }

    $q = $db->prepare('DELETE FROM commentaires_mangas WHERE commentaire_id=:commentaire_id');
    $q->execute(['commentaire_id' => htmlspecialchars($_GET['commentaireMangas'])]);
    header('Location:' .$_SERVER['HTTP_REFERER']);

   
}

if (isset($_GET['signalementMangas'])) {

    $q = $db->prepare('SELECT * FROM signalements_mangas WHERE signalement_id=:signalement_id');
    $q->execute(['signalement_id' => htmlspecialchars($_GET['signalementMangas'])]);
    $res = $q->fetch();
    if ($res['user_id'] != $_SESSION['auth']['id'] && $_SESSION['auth']['admin'] == 0) {
        header('Location: '.$_SERVER['HTTP_REFERER']);
        exit();
    }

    $q = $db->prepare('DELETE FROM signalements_mangas WHERE signalement_id=:signalement_id');
    $q->execute(['signalement_id' => htmlspecialchars($_GET['signalementMangas'])]);
    header('Location:' .$_SERVER['HTTP_REFERER']);

}

if (isset($_GET['signalement'])) {

    $q = $db->prepare('SELECT * FROM signalements WHERE signalement_id=:signalement_id');
    $q->execute(['signalement_id' => htmlspecialchars($_GET['signalement'])]);
    $res = $q->fetch();
    if ($res['user_id'] != $_SESSION['auth']['id'] && $_SESSION['auth']['admin'] == 0) {
        header('Location: '.$_SERVER['HTTP_REFERER']);
        exit();
    }

    $q = $db->prepare('DELETE FROM signalements WHERE signalement_id=:signalement_id');
    $q->execute(['signalement_id' => htmlspecialchars($_GET['signalement'])]);
    header('Location:' .$_SERVER['HTTP_REFERER']);

}


?>
