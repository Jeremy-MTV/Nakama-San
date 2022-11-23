<?php
        if(!isset($_SESSION)){
          session_start();
        }
        if(!isset($_SESSION['admin'])){
          $_SESSION['admin'] = 0;
        }
?>

<!DOCTYPE html>
<html lang="fr">


<head>
    <!-- importation des fichiers de style et du logo -->
    <meta charset="UTF-8">
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
          <a href="../page de connexion/PageInscription.html"><input type="button" value="S'inscrire"></a>
          <a href="../page de connexion/connexion1.php"><input type="button" value="Se connecter"></a>
        </div>
    <?php } else { ?>
        <div class="formulaire">
          <a href="pageProfile.php"><input type="button" value=<?php echo $_SESSION['auth']['pseudo'] ?> ></a>
          <a href="deconnexion.php"><input type="button" value="Se déconnecter"></a>
        </div>
    <?php } ?>  
</header>


<body>
  <!-- Div de l'espace admin et PHP pour pouvoir changer les différentes informations de la BD -->
    <div class="colorbox"> 
      <br>
        <center><h1 class="active">Salut <?php echo $_SESSION['auth']['pseudo'] ?>, bienvenue sur l'Espace Administrateur !</h1> <br></center>
        <?php
            require_once 'database.php';
            require_once 'verifConnexion.php';
            if (!estConnecte()) {
                header('Location: ../page de connexion/connexion1.php');
                exit();
            }
            if ($_SESSION['auth']['admin'] == 0) {
                header('Location: ../index.php');
                exit();
            }
        ?>
        <br>
        <br>
        <br>
        <br>

        <!-- Div de la suppression -->
        <div class="active">
          <div class="supp">
            <br>
        <center><h1 class="ac">Supprimer un utilisateur : </h1></center>
        <br>
        <?php
        switch ($_SESSION['admin']) {
          case '1':
            echo "L'utilisateur a bien été supprimé";
            $_SESSION['admin'] = 0;
            break;
          case '2':
            echo "Erreur : ce pseudo est le vôtre";
            $_SESSION['admin'] = 0;
            break;
          case '3':
            echo "Erreur : vous ne pouvez pas supprimer le compte d'un autre admin";
            $_SESSION['admin'] = 0;
            break;
          case '4':
            echo "Cet utilisateur n'existe pas";
            $_SESSION['admin'] = 0;
            break;
        }
        ?>
        <br>

        <center><form  method="post">
                    <input type="text" name="pseudo" size="30" placeholder="Pseudo de l'utilisateur" class="i-box" required>
                    <br><br>
                    <input type="submit" name="send" size="15" value="Supprimer l'Utilisateur" class="buton">
                    <br>
                    <br>
                    <br>
                </form></center>

      <?php
          if (isset($_POST['send'])) {
            $q = $db->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
            $q->execute(['pseudo' => htmlspecialchars($_POST['pseudo'])]);
            $res = $q->fetch(); 

            if ($res) {
              if ($_SESSION['auth']['admin'] == 2 && htmlspecialchars($_POST['pseudo']) != $_SESSION['auth']['pseudo']) {
              $q = $db->prepare("DELETE FROM users WHERE pseudo = :pseudo");
              $q->execute(['pseudo' => htmlspecialchars($_POST['pseudo'])]);
              $_SESSION['admin'] = 1;
              header('Location: espaceAdmin.php');

              } else if (htmlspecialchars($_POST['pseudo']) == $_SESSION['auth']['pseudo']) {
                $_SESSION['admin'] = 2;
                header('Location: espaceAdmin.php');

              } else if ($_SESSION['auth']['admin'] == 1 && htmlspecialchars($_POST['pseudo']) != $_SESSION['auth']['pseudo'] && $res['admin'] == 0) {
                $q = $db->prepare("DELETE FROM users WHERE pseudo = :pseudo");
                $q->execute(['pseudo' => htmlspecialchars($_POST['pseudo'])]);
                $_SESSION['admin'] = 1;
                header('Location: espaceAdmin.php');

              } else if ($_SESSION['auth']['admin'] == 1 && htmlspecialchars($_POST['pseudo']) != $_SESSION['auth']['pseudo'] && $res['admin'] != 0) {
                header('Location: espaceAdmin.php');
                $_SESSION['admin'] = 3;

              }
            } else {
              $_SESSION['admin'] = 4;
              header('Location: espaceAdmin.php');
            }
          }
          ?></div></div>


          <!-- Div de la nomination utilisateur pour admin -->
        <div class="active">
        <div class="nommer">
          <br>
        <center><h1 class="ac">Nommer un utilisateur admin  : </h1></center>
        <br>
        <br>
        <center><form  method="post">
                <input type="text" name="pseudo" size="30" placeholder="Pseudo de l'utilisateur" class="i-box" required>
                <br><br>
                <input type="submit" name="nommeAdmin" size="15" value="Nommer l'Utilisateur admin" class="buton">
                <br>
                <?php
                  switch ($_SESSION['admin']) {
                    case '5':
                      echo "Erreur : ce pseudo est le vôtre";
                      $_SESSION['admin'] = 0;
                      break;
                    case '6':
                      echo "L'utilisateur a bien été nommé admin";
                      $_SESSION['admin'] = 0;
                      break;
                    case '7':
                      echo "Erreur : cet utilisateur est déjà admin";
                      $_SESSION['admin'] = 0;
                      break;
                    case '8':
                      echo "Cet utilisateur n'existe pas";
                      $_SESSION['admin'] = 0;
                      break;
                  }
                  ?>
                <br>
                <br>
        </form></center>
        <?php

        if (isset($_POST['nommeAdmin'])) {
                    $q = $db->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
                    $q->execute(['pseudo' => htmlspecialchars($_POST['pseudo'])]);
                    $res = $q->fetch(); 

                    if ($res) {
                      if (htmlspecialchars($_POST['pseudo']) == $_SESSION['auth']['pseudo']) {
                        $_SESSION['admin'] = 5;
                        header('Location: espaceAdmin.php');

                      } else if (htmlspecialchars($_POST['pseudo']) != $_SESSION['auth']['pseudo'] && $res['admin'] == 0) {
                        $q = $db->prepare('UPDATE `users` SET `admin` = :admin WHERE `users`.`id` = :id');
                        $q->execute(array('admin' => 1, 'id' => $res['id']));
                        $_SESSION['admin'] = 6;
                        header('Location: espaceAdmin.php');
                        

                      } else if (htmlspecialchars($_POST['pseudo']) != $_SESSION['auth']['pseudo'] && $res['admin'] != 0) {
                        $_SESSION['admin'] = 7;
                        header('Location: espaceAdmin.php');
                        
                      }
                    } else {
                      $_SESSION['admin'] = 8;
                      header('Location: espaceAdmin.php');
                      
                    }
                  }
                
                  ?></div></div>

                  <!-- div pour ajouter des animes -->
    <div class="active">
    <div class="ajouterA">
      <br>
      <center><h1 class="ac">Ajouter un animé :</h1></center>
      <br>
      <br>
      <center><form  method="post">
        <input type="text" name="titre" size="30" placeholder="Titre" class="i-box" required>
        <br><br>
        <textarea type="text" name="synopsis" size="30" placeholder="Synopsis" class="i-box" required></textarea>
        <br><br>
        <input type="text" name="image" size="30" placeholder="Image" class="i-box" required>
        <br><br>
        <input type="text" name="video" size="30" placeholder="Vidéo" class="i-box" required>
        <br><br>
        <input type="submit" name="ajoutAnime" size="15" value="Ajouter l'animé !" class="buton">
        <br>
        <?php 
          if($_SESSION['admin'] == 9){
            echo "L'anime a été ajouté";
            $_SESSION['admin'] =0;
          } else if($_SESSION['admin'] == 11){
            echo "Veuillez mettre un lien Youtube valide";
            $_SESSION['admin'] =0;
          }
        ?>
        <br>
        <br>
      </form></center>
 

      <?php

        if (isset($_POST['ajoutAnime'])) {
          $video = htmlspecialchars($_POST['video']);
          for($i = 0; $i<strlen($video); $i++){
            if($video[$i]=='='){
              $table = explode('=',$video);
              $video = $table[1];
              $q = $db->prepare('INSERT INTO `animes`(`titre`, `synopsis`, `image`, `video`) VALUES(?,?,?,?)');
              $q->execute(array(htmlspecialchars($_POST['titre']), htmlspecialchars($_POST['synopsis']), htmlspecialchars($_POST['image']), $video));
              $_SESSION['admin'] = 9;
              header('Location: espaceAdmin.php');
              break;
            }
          }
          if($i==strlen($video)){
            $_SESSION['admin'] = 11;
            header('Location: espaceAdmin.php');
          }
        }

        ?></div></div>


        <!-- div pour ajouter des mangas -->
      <div class="active">
      <div class="ajouterM">
          <br>
        <center><h1 class="ac">Ajouter un manga :</h1></center>
        <br>
        <br>
        <center><form  method="post">
          <input type="text" name="titre" size="30" placeholder="Titre" class="i-box" required>
          <br><br>
          <textarea type="text" name="synopsis" size="30" placeholder="Synopsis" class="i-box" required></textarea>
          <br><br>
          <input type="text" name="image" size="30" placeholder="Image" class="i-box" required>
          <br><br>
          <input type="submit" name="ajoutManga" size="15" value="Ajouter le manga !" class="buton">
          <br>
          <?php 
            if($_SESSION['admin'] == 10){
              echo "Le manga a été ajouté";
              $_SESSION['admin'] =0;
            }
          ?>
          <br>
          <br>
        </form></center>
  

          <?php
        if (isset($_POST['ajoutManga'])) {
          $q = $db->prepare('INSERT INTO `mangas`(`titre`, `synopsis`, `image`) VALUES(?,?,?)');
          $q->execute(array(htmlspecialchars($_POST['titre']), htmlspecialchars($_POST['synopsis']), htmlspecialchars($_POST['image'])));
          $_SESSION['admin'] = 10;
          header('Location: espaceAdmin.php');
        }

        $q = $db->prepare('SELECT * FROM signalements');
        $q->execute();
        $res = $q->fetchAll();

        if ($res) {
          ?></div></div>
  
          <!-- div du signalements -->
      <center><div class="active">
      <div class="ajouterM">
        <br>
      <center><h1 class="ac">Signalements : </h1></center>
      <br>
      <br>
      <center><?php
      
              foreach($res as $ress) {
                $q = $db->prepare('SELECT pseudo FROM users WHERE id =:id');
                $q->execute(['id' => $ress['user_id']]);
                $res2 = $q->fetch();

                $q = $db->prepare('SELECT avis FROM commentaires WHERE commentaire_id =:commentaire_id');
                $q->execute(['commentaire_id' => $ress['commentaire_id']]);
                $res3 = $q->fetch();
                
                echo "Pseudo : ".$res2['pseudo']."<br> Motif : ".$ress['motif']." <br> Avis : ".$res3['avis']." <a href=\"suppression.php?commentaire=".$ress['commentaire_id']."\">Supprimer</a> ou <a href=\"suppression.php?signalement=".$ress['signalement_id']."\">Ignorer</a><br><br>";
              }

              $q = $db->prepare('SELECT * FROM signalements_mangas');
              $q->execute();
              $res = $q->fetchAll();

              foreach($res as $ress) {
                $q = $db->prepare('SELECT pseudo FROM users WHERE id =:id');
                $q->execute(['id' => $ress['user_id']]);
                $res2 = $q->fetch();

                $q = $db->prepare('SELECT avis FROM commentaires_mangas WHERE commentaire_id =:commentaire_id');
                $q->execute(['commentaire_id' => $ress['commentaire_id']]);
                $res3 = $q->fetch();
                
                echo "Pseudo : ".$res2['pseudo']."<br> Motif : ".$ress['motif']." <br> Avis : ".$res3['avis']." <a href=\"suppression.php?commentaireMangas=".$ress['commentaire_id']."\">Supprimer</a> ou ou <a href=\"suppression.php?signalementMangas=".$ress['signalement_id']."\">Ignorer</a><br><br>";
              }
            }
            echo "<br><br>"


    ?></center></div></div></center>
       
          


        
    </div>
</body>



</html>
