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

<?php
  
require_once 'database.php';
  
 $anime = $db->query('SELECT titre,image,id FROM animes ORDER BY id DESC');
 
?>

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
    <div class="colorbox">
    <br>
    <br>
    <br>
    <br>
    <div class="heros container">
        <br>
        <br>
    <center><div class="left">
              <form action="recherche.php" method="post">
                <div class="input">
                  <input type="text" name="recherche" placeholder="Trouve ton coup de coeur">
                  <input type="number" name="note"  placeholder="Note entre 0 et 5" >
                  <button type="submit"><span>Go</span></button>
                </div>
              </form>
            </div></center>
    </div>

    <br>        
    <br>
    <br>
    <!-- affichage en PHP des div de chaques animes du site -->
    <?php if($anime->rowCount() > 0) { ?>
        <?php while($a = $anime->fetch()) { ?>
            <div class="active">
            <div class="ajouterM">
            <center><h1 class="ac"><?= $a['titre'] ?></h1></center>
            <br>
            <center><a href="animeV2.php?anime=<?php echo $a['id']?>"><img src=<?php echo $a['image'] ?> width=60% height=40%></a></center>
            <br>
            </div>
            </div>
        <?php } ?> 
    <?php } ?>
        </div>
</body>

</html>
