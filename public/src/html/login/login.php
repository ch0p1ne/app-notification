<?php
session_cache_expire(120);
$cache_expire = session_cache_expire(); 
session_start();
require_once "inc/config.inc.php" ;
require_once "inc/functions.inc.php" ;
$debuger = 'reussi';

$error_msg = "";
if ( isset( $_POST['email'] ) && isset( $_POST['password'] ) ) {
	$email    = $_POST['email'];
	$password = $_POST['password'];
	$sqlStatement = 'SELECT * FROM users WHERE email = :email';

	$statement = $db->prepare( $sqlStatement,[PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY] );
	$result    = $statement->execute( [ ':email' => $email]) ;
	$user      = $statement->fetch();


	// deuxieme requete pour obtenir le non du fournisseur si l'utilisateur est a la fois user et provider
	$sqlStatement2 = 'SELECT provider_name, rbtmq_order_queue from users inner join providers on provider_id = id_provider where id = :id';
	$statement = $db->prepare( $sqlStatement2,[PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY] );
	$result    = $statement->execute( [ ':id' => $user['id']]) ;
	$provider = $statement->fetch();


	//Vérification du mot de passe 
	if ( $user  && password_verify( $password, $user['password'] ) ) {
		$_SESSION['userid'] = $user['id']; 


		// # Ajoute en session du non du fournisseur 
		// ~ SI l'utilisateur est un fournisseur aussi
		if($provider) {
			if($user['positon'] == 'admin') {

				// VIOLATION DE LA POLITIQUE d'utilisation, un admin ne doit pas avoir un compte
				// qui est aussi fournisseur, ce sont des comptes fournie pour travailler
				//  pas pour commercer, il faut creer un compte distinct alors
				$_SESSION['violation'] = "Votre compte va etre bannis definitivement";
			}
			$_SESSION['provider_name'] = $provider['provider_name']; 

			// # Ajoute en session le nom de la queue
			// ~ Si la position du l'utilisateur est admin ou fournisseur
			// ~ C'est la requete vers la base qui le determine,
			$_SESSION['order_queue'] = $provider['rbtmq_order_queue']; 
		}






		$_SESSION['site'] = "mpos";
		
		$_SESSION['sess_name'] = $user['vorname'];
		$_SESSION['sess_email'] = $user['email'];
        $_SESSION['sess_userrole'] = $user['position']; 
 

		if( $_SESSION['sess_userrole'] == "admin1") {
			header('Location: /app-notification');
		}
		
		elseif( $_SESSION['sess_userrole'] == "admin") {
			header('Location: /app-notification');
		}
		
		elseif( $_SESSION['sess_userrole'] == "casher") {
			header('Location: main44/index.php');
		}
		
		// elseif( $_SESSION['sess_userrole'] == "casher") {
			// header('Location: main1/pos_web/basic2.php');
		// }
		
		
	 
 
		//L'utilisateur souhaite-t-il rester connecté?
	 
} else {
		$error_msg = "L'email ou le mot de passe n'était pas valide";
	}	 

}

$email_value = "";
if ( isset( $_POST['email'] ) ) {
	$email_value = htmlentities( $_POST['email'] );
}

$site_title = "Login";
include "inc/header.inc.php" ;
?>
<div class="row">
    <form action="login.php" method="post" class="col s12">
        <h1 class="<?php echo $site_color_accent_text; ?>">Login</h1>

		<?php
		if ( isset( $error_msg ) && ! empty( $error_msg ) ) {
			echo $error_msg;
		}
		?>
        <div class="row">
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input type="email" name="email" id="inputEmail" class="validate" value="<?php echo $email_value; ?>"
                       required autofocus>
                <label for="inputEmail">E-Mail</label>
            </div>

            <div class="input-field col s12">
                <input type="password" name="password" id="inputPassword" class="validate" required>
                <label for="inputPassword">Password</label>
            </div>

            <p>
                <label>
                    <input type="checkbox" value="remember-me" id="remember-me" name="angemeldet_bleiben" value="1"
                           checked="checked"/>
                    <span>Enregistrer votre mot de passe</span>
                </label>
            </p>


            <button class="<?php echo $site_color_accent; ?> btn waves-effect waves-light col s12 m6 l3" type="submit"
                    name="action">Connexion
                <i class="material-icons right">send</i>
            </button>

        </div>
    </form>
</div>

<?php
include "inc/footer.inc.php" 
?>
