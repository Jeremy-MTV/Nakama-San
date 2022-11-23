<?php
session_start();
require_once 'database.php';
require_once 'verifConnexion.php';
if (!estConnecte()) {
    header('Location: ../page de connexion/connexion1.php');
    exit();
} 

?>

<!DOCTYPE HTML>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/animemanga.css">
    <link rel="icon" type="image/png" href="../image/logo.png">
  <title>Motifs du signalment</title>
</head>

<header>
    <div class="droite"><a href="../index.php" ><img src="../image/Nakama San2.png" width=40% height=40%></a></div>

    <?php 
require_once 'database.php';
require_once 'verifConnexion.php';
if(!estConnecte()){?>
    <div class="formulaire">
      <a href="../page de connexion/PageInscription.php"><input type="button" value="S'inscrire"></a>
      <a href="../page de connexion/connexion1.php"><input type="button" value="Se connecter"></a>
     </div>
<?php } else {?>
    <div class="formulaire">
      <a href="pageProfile.php"><input type="button" value=<?php echo $_SESSION['auth']['pseudo'] ?> ></a>
      <a href="deconnexion.php"><input type="button" value="Se déconnecter"></a>
    </div>
<?php } ?>
 
</header>

<body>
    <div class="colorbox">
<?php
    if (isset($_GET['anime'])) { ?>
    <a href="animeV2.php?anime=<?php echo htmlspecialchars($_GET['anime'])?>"><input type="button" value="Retour aux commentaires"></a>
    <br>
    <br>
    <?php
    } else {
        ?>
           <a href="manga.php?manga=<?php echo htmlspecialchars($_GET['manga'])?>"><input type="button" value="Retour aux commentaires"></a> 
           <?php } ?>
           <center><div class="active2"><h1>Veuillez choisir le motif du signalement</h1><br>

    <form  method="post">
        Motifs : <br><input type="radio" name="motif" value="Commentaire violent"required> Commentaire violent <br>
                 <input type="radio" name="motif" value="Commentaire offensant ou haineux" required> Commentaire offensant ou haineux <br>
                 <input type="radio" name="motif" value="Commentaire à but commercial" required> Commentaire à but commercial <br>
                 <input type="radio" name="motif" value="Commentaire menaçant" required> Commentaire menaçant <br>
                 <input type="radio" name="motif" value="Autre" required> Autre <br>
                 <br>
    <input type="submit" name="submit" size="15" value="Envoyer">
    <br>
    <br>
    </form>

    <?php
     if (isset($_POST['submit'])) {
         if (isset($_GET['anime'])) {
            $q = $db->prepare('SELECT * FROM signalements WHERE commentaire_id=:commentaire_id');
            $q->execute(['commentaire_id' => htmlspecialchars($_GET['commentaire'])]);
            $res = $q->rowCount();
            if ($res != 0) {
                echo "Ce commentaire a déjà été signalé";
                    header('Refresh: 3; animeV2.php?anime='.htmlspecialchars($_GET['anime']));
            } else {    
                $q = $db->prepare('SELECT * FROM commentaires WHERE commentaire_id=:commentaire_id');
                $q->execute(['commentaire_id' => htmlspecialchars($_GET['commentaire'])]);
                $res = $q->fetch();
            $u = $res['user_id'];
            $c = $res['avis'];
            
            
            $q = $db->prepare('INSERT INTO `signalements`(`user_id`,`commentaire_id`,`motif`, `commentaire`) VALUES(?,?,?,?)');
            $q->execute(array($u, htmlspecialchars($_GET['commentaire']), htmlspecialchars($_POST['motif']), $c));
            echo "Le commentaire a bien été signalé";
                header('Refresh: 3; animeV2.php?anime='.htmlspecialchars($_GET['anime']));

            }
        } else {
            $q = $db->prepare('SELECT * FROM signalements_mangas WHERE commentaire_id=:commentaire_id');
            $q->execute(['commentaire_id' => htmlspecialchars($_GET['commentaireMangas'])]);
            $res = $q->rowCount();
            if ($res != 0) {
                echo "Ce commentaire a déjà été signalé";
                header('Refresh: 3; manga.php?manga='.htmlspecialchars($_GET['manga']));
            } else {    
                $q = $db->prepare('SELECT * FROM commentaires_mangas WHERE commentaire_id=:commentaire_id');
                $q->execute(['commentaire_id' => htmlspecialchars($_GET['commentaireMangas'])]);
                $res = $q->fetch();
            $u = $res['user_id'];
            $c = $res['avis'];
            
            
            $q = $db->prepare('INSERT INTO `signalements_mangas`(`user_id`,`commentaire_id`,`motif`, `commentaire`) VALUES(?,?,?,?)');
            $q->execute(array($u, htmlspecialchars($_GET['commentaireMangas']), htmlspecialchars($_POST['motif']), $c));
            echo "Le commentaire a bien été signalé";
            header('Refresh: 3; manga.php?manga='.htmlspecialchars($_GET['manga']));

            }
        }
        
        
     }

    ?></div></center>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    </div>
</body> 
</html>

