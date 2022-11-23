<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/profile.css">
    <title>Nakama San</title>
    <link rel="icon" type="image/png" href="../image/logo.png">

</head>



<header>
    <div class="droite"><a href="../index.php" ><img src="../image/Nakama San2.png" width=40% height=40%></a></div>

<?php 
require_once 'database.php';
require_once 'verifConnexion.php';
if(!estConnecte()){
    
      header('Location: ../page de connexion/connexion1.php');
 } elseif($_SESSION['auth']['admin'] != 0) {?>
    <div class="formulaire">
      <a href="espaceAdmin.php"><input type="button" value="Espace Administrateur" ></a>
      <a href="deconnexion.php"><input type="button" value="Se déconnecter"></a>
    </div>
<?php } else { ?>
    <div class="formulaire">
      <a href="pageProfile.php"><input type="button" value=<?php echo $_SESSION['auth']['pseudo'] ?> ></a>
      <a href="deconnexion.php"><input type="button" value="Se déconnecter"></a>
    </div>
<?php } ?>
   
</header>

<body>
    <div class="colorbox"> 
       
        
        <center><h1 class="active">Salut <?php echo $_SESSION['auth']['pseudo'] ?>, bienvenue sur ton espace personnel !</h1> <br></center>

        

        <center><div class="active2">
          <div class="ajouterM">
          <br>
        <center><h2>Mes derniers commentaires : </h2> <br></center>

        <center><?php

          $q = $db->prepare('SELECT * FROM commentaires WHERE user_id=:user_id');
          $q->execute(['user_id' => $_SESSION['auth']['id']]);
          $res = $q->fetchAll();
          $n = $q->rowCount();


          $res = array_reverse($res);
          $i = 0;

          foreach($res as $ress) {
              if ($i == 5) {
                  break;
              }
              $i++;
            $q = $db->prepare('SELECT titre FROM animes WHERE id =:id');
            $q->execute(['id' => $ress['anime_id']]);
            $res2 = $q->fetch();
            echo "Animé : ".$res2['titre']."<br> Note : ".$ress['note']."/5 <br> Avis : ".$ress['avis']." <a href=\"suppression.php?commentaire=".$ress['commentaire_id']."\">Supprimer</a><br><br>";
          }

          $q = $db->prepare('SELECT * FROM commentaires_mangas WHERE user_id=:user_id');
          $q->execute(['user_id' => $_SESSION['auth']['id']]);
          $res = $q->fetchAll();

          $n2 = $q->rowCount();

          if ($n == 0 && $n2 == 0) {
            echo "Vous n'avez pas posté de commentaire.";
          }

          $res = array_reverse($res);
          $i = 0;

          foreach($res as $ress) {
              if ($i == 5) {
                  break;
              }
              $i++;
            $q = $db->prepare('SELECT titre FROM mangas WHERE id =:id');
            $q->execute(['id' => $ress['manga_id']]);
            $res2 = $q->fetch();
            echo "Mangas : ".$res2['titre']."<br> Note : ".$ress['note']."/5 <br> Avis : ".$ress['avis']." <a href=\"suppression.php?commentaireMangas=".$ress['commentaire_id']."\">Supprimer</a><br><br>";
          }
          ?></center>
          </div></div></center>

          <center><div class="active2">
          <div class="ajouterM">
          <br>
          <h2>Modifier mon mot de Passe : </h2>
          <br>
              <form  method="post">
                      <input type="password" name="pwd" size="30" placeholder="Mot de passe actuel" class="i-box" required>
                      <br><br>
                      <input type="password" name="newpwd" size="30" placeholder="Nouveau mot de passe" class="i-box" required>
                      <br><br>
                      <input type="password" name="Cnewpwd" size="30" placeholder="Confirmation du nouveau mot de passe" class="i-box" required>
                      <br><br>
                      <input type="submit" name="send" size="15" value="Modifier mon mot de Passe" class="buton">
                      <br>
                      <br>
                      
              </form>

          <?php

              if (isset($_POST['send'])) {
                  $q = $db->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
                  $q->execute(['pseudo' => $_SESSION['auth']['pseudo']]);
                  $res = $q->fetch(); 
                  if (password_verify(htmlspecialchars($_POST['pwd']), $res['password'])) {
                      if ($_POST['newpwd'] == $_POST['Cnewpwd']) {
                          if (strlen($_POST['newpwd']) >= 8 && strlen($_POST['newpwd']) <= 32) {

                              $option = [
                                  'cost' => 12,
                              ];

                              $hashpwd = password_hash(htmlspecialchars($_POST['newpwd']), PASSWORD_BCRYPT, $option);
                              $q = $db->prepare("UPDATE `users` SET `password` = :password WHERE `users`.`id` = :id");
                              $q->execute(array('password' => $hashpwd, 'id' => $_SESSION['auth']['id']));
                              echo "Votre mot de passe a bien été modifié";

                          } else {
                              echo  "Veuillez saisir un nouveau mot de passe ayant entre 8 et 32 caractères";
                          }
                      } else {
                          echo "Les mot de passes ne correspondent pas";
                      }
                  } else {
                      echo "Mot de passe incorrect";
                  }
              }

              ?></div></div></center>



        
    </div>
</body>



</html>
