<?php
session_start();
require_once 'database.php';
require_once 'verifConnexion.php';

if (isset($_POST['formsend'])) {
  $_SESSION['sauv'];
  if ($_POST['note'] >= 0 && $_POST['note'] <= 5) {
    $q = $db->prepare('INSERT INTO `commentaires_mangas`(`user_id`,`manga_id`,`note`, `avis`) VALUES(?,?,?,?)');
    $q->execute(array($_SESSION['auth']['id'],htmlspecialchars($_GET['manga']), htmlspecialchars($_POST['note']), htmlspecialchars($_POST['avis'])));
    $_SESSION['sauv'] = 1;
    header('Location: manga.php?manga='.$_GET['manga']);exit();
    } else {
      $_SESSION['sauv'] = 2;
      header('Location: manga.php?manga='.$_GET['manga']);exit();
    }
   }

?>

<!DOCTYPE html>
<html lang="fr">
<meta charset="UTF-8" />

<head>
    <!-- importation des fichiers de style et du logo -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/animemanga.css">
    <title>Nakama San</title>
    <link rel="icon" type="image/png" href="../image/logo.png">
</head>

<header>
    <!-- Logo à gauche et bouton de connexion/inscription qui change en fonction de la connexion -->
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
  <!-- div pour les mangas et récupération en PHP des informations du manga à partir de l'ID -->
<div class="colorbox">
<?php

        if (isset($_GET['manga'])) {
          $idA = htmlspecialchars($_GET['manga']);
          $q = $db->prepare('SELECT * FROM commentaires_mangas WHERE manga_id=:manga_id');
          $q->execute(['manga_id' => $idA]);
          $res = $q->fetchALL();
          $affMoy = $q->rowCount();
          if ($res) {
            $moy = 0;
            foreach($res as $ress) {
              $moy = $moy + $ress['note'];
            }
            $q = $db->prepare('SELECT * FROM commentaires_mangas WHERE manga_id=:manga_id');
            $q->execute(['manga_id' => $idA]);
            $resF = $q->rowCount();
            

            $moy = $moy / $resF;

            $q = $db->prepare('UPDATE `mangas` SET `note` = :note WHERE `mangas`.`id` = :id');
            $q->execute(array('note' => $moy, 'id' => $idA));
            


          } else {
            $q = $db->prepare('UPDATE `mangas` SET `note` = :note WHERE `mangas`.`id` = :id');
            $q->execute(array('note' => -1, 'id' => $idA));
          }


        
          $q = $db->prepare('SELECT * FROM mangas WHERE id=:id');
          $q->execute(['id' => $idA]);
          $res = $q->fetch();?>
          <center><h1 class="active"><?php echo $res['titre'];?></h1></center>
          <?php if ($res['note'] != -1 && $affMoy != 0){ echo "<center><div class=\"active\"><br>";}?>
          <?php if ($res['note'] != -1 && $affMoy != 0) {
            echo "<h2>Note moyenne : ".$res['note']."/5 </h2><br>";
          }?>
          <?php if($res['note'] != -1 && $affMoy != 0){ echo "</div></center>"; } ?>

          <div class="aligné">
          <div class="active"><center><br><img src="<?php echo $res['image']; ?>" width="80%" height="70%"><br><br></center></div>
          <div class="active2"><center><br><h2>Synopsis</h2></center><br>
          <p class="synopsis"><center><?php echo $res['synopsis'];?></center></p><br></div>
          </div>


          <div class="aligné2">
          <center><div class="active">
          <center><br><h3>Commentaires (<?php if (isset($resF)){echo $resF;}else{echo 0;}; ?> commentaires) </h3></center>
          <div class="commentaires">
          <?php if (!estConnecte()) {
            echo "<center><br><a href=\"../page de connexion/connexion1.php\">Me connecter pour consulter tous les commentaires</a></center><br><br>";
          }

          $q = $db->prepare('SELECT user_id,avis,note,commentaire_id FROM commentaires_mangas WHERE manga_id=:manga_id');
          $q->execute(['manga_id' => $idA]);
          $res = $q->fetchAll();
          $res = array_reverse($res);
          $i = 0;
          
          
          foreach($res as $ress) {
            if (!estConnecte()) {
              if ($i == 3) {
                break;
              }
              $i++;
          }
            $q = $db->prepare('SELECT pseudo FROM users WHERE id =:id');
            $q->execute(['id' => $ress['user_id']]);
            $res2 = $q->fetch();
            if (estConnecte() && $_SESSION['auth']['admin'] != 0) { ?>
              <center>
                 <?php echo "Pseudo : ".$res2['pseudo']."<br> Note : ".$ress['note']."/5 <br> Avis : ".$ress['avis']." <a href=\"suppression.php?commentaireMangas=".$ress['commentaire_id']."\">Supprimer</a><br><br>"; ?>
            </center>
            <?php } else if (estConnecte() && $_SESSION['auth']['admin'] == 0 && $_SESSION['auth']['pseudo'] == $res2['pseudo']) {
              echo "Pseudo : ".$res2['pseudo']."<br> Note : ".$ress['note']."/5 <br> Avis : ".$ress['avis']." <a href=\"suppression.php?commentaireMangas=".$ress['commentaire_id']."\">Supprimer mon commentaire</a><br><br>";

            } else if (estConnecte() && $_SESSION['auth']['admin'] == 0 && $_SESSION['auth']['pseudo'] != $res2['pseudo']) {
              echo "Pseudo : ".$res2['pseudo']."<br> Note : ".$ress['note']."/5 <br> Avis : ".$ress['avis']." <a href=\"pageMotifs.php?commentaireMangas=".$ress['commentaire_id']."&manga=".$idA."\">Signaler le commentaire</a><br><br>";

            
            } else {
              echo "Pseudo : ".$res2['pseudo']."<br> Note : ".$ress['note']."/5 <br> Avis : ".$ress['avis']." <br><br>";
            }
          }
          ?></div></div></center><?php 
        }
      if (estConnecte()) {
?>

<!-- div pour commentaire -->
  <center><div class="active">
    <br>
    <center><h3>Ajouter un commentaire</h3></center>
    <br>
      <center><form  method="post">
        <textarea type="text" name="avis" size="30" placeholder="Votre avis" class="i-box" required></textarea>
        <br><br>
        <input type="number" name="note" size="30" placeholder="Note entre 0 et 5" class="i-box" required>
        <br><br>
        <input type="submit" name="formsend" size="15" value="Ajouter mon commentaire !" class="buton">
        <br>
        <br>
       <?php
        if(isset($_SESSION['sauv'])){
          if($_SESSION['sauv'] == 1){
            echo "Le commentaire a bien été ajouté !!";
          } else if($_SESSION['sauv'] == 2){
            echo "Vous devez saisir une note entre 0 et 5";
          }
         $_SESSION['sauv'] = 0;
       }
     }
      ?>
      </form></center>
     
 </div></center>
 <br>
      <center><a href="pageManga.php"><input type="button" value="Choisir un autre manga"></a></center>
      <br>
      </div>
      
</div>

</body>
</html>




