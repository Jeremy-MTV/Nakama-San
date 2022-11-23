<!-- Configuration de la base de donnée en fonction du nom de la table du site -->
<?php
$user = 'root';
$pass = '';

try {
$db = new PDO('mysql:host=localhost;dbname=nakamasan2', $user, $pass);
} catch (PDOException $e) {
    print "Erreur :" . $e->getMessage() . "<br/>";
    die;
}

?>
