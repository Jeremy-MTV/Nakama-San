<!DOCTYPE html>
<html lang="fr">
<meta charset="UTF-8" />

<head>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/recherche.css">
    <link rel="stylesheet" href="../css/animemanga.css">
    <title>Nakama San</title>
    <link rel="icon" type="image/png" href="../image/logo.png">
</head>

<?php

//   Requete SQL pour pouvoir selectionner dans la BD les éléments
require_once 'database.php';
  
 $anime = $db->query('SELECT titre,image,id FROM animes ORDER BY id DESC');
 $manga = $db->query('SELECT titre,image,id FROM mangas ORDER BY id DESC');
 
 
 //   Requete SQL pour pouvoir selectionner dans la BD les éléments en fonction de la recherche
  if(!empty($_POST['recherche']) && empty($_POST['note'])) {
    $recherche = htmlspecialchars($_POST['recherche']);

    $anime = $db->query("SELECT titre,image,id FROM animes WHERE( titre LIKE '%$recherche%' OR titre LIKE '$recherche%' OR titre LIKE '%$recherche' OR titre LIKE '$recherche' ) ");   
    $manga = $db->query("SELECT titre,image,id FROM mangas WHERE( titre LIKE '%$recherche%' OR titre LIKE '$recherche%' OR titre LIKE '%$recherche' OR titre LIKE '$recherche' ) ");
    
 }

  if(!empty($_POST['recherche']) && !empty($_POST['note'])) {
    $recherche = htmlspecialchars($_POST['recherche']);
    $note = htmlspecialchars($_POST['note']);

    $anime = $db->query("SELECT titre,image,id FROM animes WHERE( titre LIKE '%$recherche%' OR titre LIKE '$recherche%' OR titre LIKE '%$recherche' OR titre LIKE '$recherche' ) AND note >= $note");   
    $manga = $db->query("SELECT titre,image,id FROM mangas WHERE( titre LIKE '%$recherche%' OR titre LIKE '$recherche%' OR titre LIKE '%$recherche' OR titre LIKE '$recherche' ) AND note >= $note");
    
 }

 if(empty($_POST['recherche']) && !empty($_POST['note'])) {
    $note = htmlspecialchars($_POST['note']);

    $anime = $db->query("SELECT titre,image,id FROM animes WHERE note >= $note");   
    $manga = $db->query("SELECT titre,image,id FROM mangas WHERE note >= $note");
    
 }
 
?>

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
<?php } elseif(estconnecte()) {?>
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
    <div class="heros container">
        <br>
        <br>
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
    <div class="conteneur">
    <?php if(empty($_POST['recherche']) && empty($_POST['note'])) { ?>
        <ul>
            <br>
            <center><h1 class="active">Vous n'avez rien saisi, recommencez !</h1></center>
        </ul>
    <?php } else if (!empty($_POST['note']) && ($_POST['note'] < 0 || $_POST['note'] > 5)) { ?>
    <ul>
    <br>
    <center><h1 class="active">Veuillez saisir une note entre 0 et 5 </h1></center>
  </ul>
    <?php } else {
            if($anime->rowCount() > 0) { ?>
                <ul>
                <?php while($a = $anime->fetch()) { ?>
                    <div class="active">
                    <div class="supp">
                    <center><h1 class="active"><?= $a['titre'] ?> (anime)</h1></center>
                    <br>
                    <center><a href="animeV2.php?anime=<?php echo $a['id']?>"><img src=<?php echo $a['image'] ?> width=60% height=40%></a></center>
                    <br>
                    </div>
                </div>
                <?php } ?>
                </ul>
                <?php } if($manga->rowCount() > 0) { ?>
                <ul>
                <?php while($b = $manga->fetch() ) { ?>
                    <div class="active">
                    <div class="supp">
                    <center><h1 class="active"><?= $b['titre'] ?> (manga)</h1></center>
                    <br>
                    <center><a href="manga.php?manga=<?php echo $b['id']?>"><img src=<?php echo $b['image'] ?> width=60% height=40%></a></center>
                    <br>
                    </div>
                </div>
                <?php } ?>
                <ul>
            <?php } if ($manga->rowCount() == 0 && $anime->rowCount() == 0) { 
                        if (!empty($_POST['recherche']) && empty($_POST['note'])) {?>
                            <center><h1 class="active">Aucun résultat pour: "<?= $recherche ?>"...</h1></center>
                        <?php } 

                        else if  (empty($_POST['recherche']) && !empty($_POST['note']) && $_POST['note'] >= 0 && $_POST['note'] <= 5) { ?>
                        <center><h1 class="active">Aucun résultat avec <?= $note ?>/5 comme note minimale...</h1></center>
                        <?php } 

                        else { ?>
                        <center><h1 class="active">Aucun résultat pour : "<?= $recherche ?>" avec <?= $note ?>/5 comme note minimale...</h1></center>
                        <?php }
                        }
                     } ?>
                     <br>
                     <br>
            </div>     
    <br>
        <br>
        <br>
        <br><br>
        <br>
        <br>
        <br>   
    </div>
   

  
    
      
  
</body>

</html>