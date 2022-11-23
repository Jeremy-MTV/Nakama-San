<?php 
if(!isset($_SESSION)){session_start();}
require_once '../php/database.php';
require_once '../php/verifConnexion.php';
if (estConnecte() == true) {
  header('Location: ../index.php');
  exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>

	<title>Nakama San</title>
	<meta charset="UTF-8">

<!--Importation des polices pour la page de connexion-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Teko:wght@700&display=swap" rel="stylesheet">

<!--Importation des pages de style-->
	<link rel="stylesheet" type="text/css" href="../css/pagedeconnexion.css">
	<link rel="icon" type="image/png" href="../image/logo.png">


	</head>
		<body>
			<div class="limiter">
				<div class="container-login100">
					<div class="corps">			
					<div class="wrap-login100">
						<div class="login100-pic js-tilt" data-tilt>
						<center><a href="../index.php"><img src="../image/logo.png" width=80% alt="IMG"></a></center>
						</div>

						<form  method="post" class="login100-form validate-form">
							<p class="login100-form-title">
								INSCRIPTION
							<p>

							<div class="wrap-input100 validate-input" data-validate = "Un Pseudo est requis">
								<input class="input100" type="text" name="pseudo" placeholder="Pseudo" required>
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="fa fa-envelope" aria-hidden="true"></i>
								</span>
							</div>

                            <div class="wrap-input100 validate-input" data-validate = "Une adresse mail correcte est requise: ex@abc.xyz">
								<input class="input100" type="text" name="email" placeholder="Email" required>
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="fa fa-envelope" aria-hidden="true"></i>
								</span>
							</div>

							<div class="wrap-input100 validate-input" data-validate = "Un Mot De Passe est requis">
								<input class="input100" type="password" name="pwd" placeholder="Mot De Passe" required>
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="fa fa-lock" aria-hidden="true"></i>
								</span>
							</div>
							
							<div class="wrap-input100 validate-input" data-validate = "Un Mot De Passe est requis">
								<input class="input100" type="password" name="pwd1" placeholder="Confirmez votre Mot De Passe" required>
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="fa fa-lock" aria-hidden="true"></i>
								</span>
							</div>

                            <?php
if(isset($_POST['formlogin'])) {
  if($_POST['email'] != "" && $_POST['pseudo'] != "" && $_POST['pwd'] != "") {
    if ($_POST['pwd'] == $_POST['pwd1']) {
    $option = [
      'cost' => 12,
    ];
	if (strlen(htmlspecialchars($_POST['pwd'])) >= 8 && strlen(htmlspecialchars($_POST['pwd'])) <= 32 ) {

	
    include '../php/database.php';
    global $db;
    $hashpwd = password_hash(htmlspecialchars($_POST['pwd']), PASSWORD_BCRYPT, $option);

    $a = $db->prepare("SELECT email FROM users WHERE email = :email");
    $a->execute(['email' => htmlspecialchars($_POST['email'])]);
    $res = $a->rowCount();

    $c = $db->prepare("SELECT pseudo FROM users WHERE pseudo = :pseudo");
    $c->execute(['pseudo' => htmlspecialchars($_POST['pseudo'])]);
    $res1 = $c->rowCount();

    if($res == 0 && $res1 == 0) {
      $q = $db->prepare('INSERT INTO `users`(`pseudo`,`email`,`password`) VALUES(?,?,?)');
      $q->execute(array(htmlspecialchars($_POST['pseudo']), htmlspecialchars($_POST['email']), $hashpwd)); 
      echo "<center><p>Inscription réussie ! </p></center><br>";

  } else if ($res != 0 && $res1 == 0) {
    echo "<center><p>Cette email est déjà enregistré.</p></center> <br>";

  } else if ($res == 0 && $res1 != 0) {
    echo "<center><p>Ce pseudo est déjà enregistré.</p></center> <br>";

  } else if ($res != 0 && $res1 != 0) {
    echo "<center><p>Cette email déjà enregistré.</p></center> <br>";
  }
} else {
	echo "<center><p>Veuillez saisir un mot de passe ayant entre 8 et 32 caractères</p></center> <br>";
}

  } else {
    echo "<center><p>Les Mots de Passes ne sont pas identiques.</p></center><br>";
  }

} else {
    echo "<center><p>Erreur : Veuillez recommencer.</p></center>";
  }
}

?>


							<div class="container-login100-form-btn">
								<button  class="login100-form-btn" type="submit" name="formlogin">
									Inscrivez-vous
								</button>
							</div>
							<br>
							<br>

							<div class="text-center">
								<center><a class="txt2" href="connexion1.php">
									Se Connecter !
								</a></center>
							</div>

						</form>
					</div>
					</div>
				</div>
			</div>
	
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>

   
</body>
</html>
