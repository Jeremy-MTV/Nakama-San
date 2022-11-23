<?php 
 if(!isset($_SESSION)){ 
	 session_start(); 
 } 
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
	<link rel="icon" type="image/png" href="../IO2/image/logo.png">

	</head>
		<body>
	
			<div class="limiter">
				<div class="container-login100">
					<div class="corps">			
					<div class="wrap-login100">
						<div class="login100-pic js-tilt" data-tilt>
							<center><a href="../index.php"><img src="../image/logo.png" width=80% alt="IMG"></a></center>
						</div>

						<form method="post" class="login100-form validate-form">
							<p class="login100-form-title">
								CONNEXION
							<p>

							<div class="wrap-input100 validate-input" data-validate = "Un Pseudo correcte est requis">
								<input class="input100" type="text" name="pseudo" placeholder="Pseudo" required>
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

							<div class="wrap-input100 validate-input">
								<label class="checkbox">
									<br>
									<center><input type="checkbox" name="remember"> Se souvenir de moi </center>
								</label>
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="fa fa-lock" aria-hidden="true"></i>
								</span>
							</div>
                            <?php
  

  if(isset($_POST['formlogin'])) {
        $q = $db->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
        $q->execute(['pseudo' => htmlspecialchars($_POST['pseudo'])]);
        $res = $q->fetch();  
        
        if($res) {
            if (password_verify(htmlspecialchars($_POST['pwd']), $res['password'])) {
              $_SESSION['auth'] = $res;
              if (isset($_POST['remember'])) {
                setcookie('rester_connecte', $res["id"]."-----".sha1($res["pseudo"].$res["password"]), time() + 3600 * 24 *3, '/');
              }
              header('Location: ../php/pageProfile.php');
            } else {
                echo "<br><center><p style=\"color: red\">Mot de Passe incorrect</p><br></center>";
            }
        
        } else {
            echo "<br><center><p style=\"color: red\">Ce compte n'existe pas</p><br></center>";
        }
    }

    ?>

			
							<div class="container-login100-form-btn">
								<button class="login100-form-btn" type="submit" name="formlogin">
									Connexion
								</button>
							</div>
							<br>
							<br>

							<div class="text-center">
								
						
								<center><a class="txt2" href="PageInscription.php">
									Creer un compte 
									<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
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
	<script src="connexion1.js"></script>

</body>
</html>
