<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">
    <title>Nakama San</title>
    <link rel="icon" type="image/png" href="image/logo.png">
</head>



<header>
    <div class="droite"><a href="index.php" ><img src="image/Nakama San2.png" width=40% height=40%></a></div>
           

<?php 
require_once 'php/database.php';
require_once 'php/verifConnexion.php';
if(!estConnecte()){?>
    <div class="formulaire">
      <a href="page de connexion/PageInscription.php"><input type="button" value="S'inscrire"></a>
      <a href="page de connexion/connexion1.php"><input type="button" value="Se connecter"></a>
     </div>
<?php } else {?>
    <div class="formulaire">
      <a href="php/pageProfile.php"><input type="button" value=<?php echo $_SESSION['auth']['pseudo'] ?> ></a>
      <a href="php/deconnexion.php"><input type="button" value="Se déconnecter"></a>
    </div>
<?php } ?>
  </header>



<body>
    <div class="corps"> 
        <video autoplay="autoplay" muted="" loop="infinite" src="video/amv.mp4"></video>
        <div class="hero container">

            <div class="left">
              <form action="php/recherche.php" method="post">
                <div class="input">
                  <input type="text" name="recherche"  placeholder="Trouve ton coup de coeur">
                  <input type="number" name="note"  placeholder="Note entre 0 et 5" >
                  <button type="submit"><span>Go</span></button>
                </div>
              </form>
            </div>
        
            <div class="right">
                
              <section  class="box1">
                <a href="php/pageAnime.php"><div class="content">
                  <h2 class="active">Les Animés</h2>
                </div></a>
              </section>
              

              <section class="box2">
              <div class="content">
              <a href="php/pageManga.php"><h2>Les Mangas</h2></a>
                </div>
              </section>

            </div>
          </div>
    </div>
</body>
</html>