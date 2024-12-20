<?php
/**
* Un script de connexion complet avec inscription et espace membres.
 *
 * @author: Nils Reimers / http://www.php-einfach.de/experte/php-codebeispiele/loginscript/
 * @license: GNU GPLv3
 */
include_once( "password.inc.php" );
require_once( "config.inc.php" );

/**
 * Vérifie que l'utilisateur est connecté.
 * @return Renvoie la ligne de l'utilisateur connecté
 */
function check_user_basic() {
	global $db;

	if ( ! isset( $_SESSION['userid'] ) && isset( $_COOKIE['identifier'] ) && isset( $_COOKIE['securitytoken'] ) ) {
		$identifier    = $_COOKIE['identifier'];
		$securitytoken = $_COOKIE['securitytoken'];

		$statement         = $db->prepare( "SELECT * FROM securitytokens WHERE identifier = ?" );
		$result            = $statement->execute( array( $identifier ) );
		$securitytoken_row = $statement->fetch();

		if ( sha1( $securitytoken ) !== $securitytoken_row['securitytoken'] ) {
			// Le jeton de sécurité a probablement été volé
// Ici, si nécessaire, un avertissement ou similaire. Spectacle

		} else { // Le jeton était correct
// Définir un nouveau jeton 
			$neuer_securitytoken = random_string();
			$insert              = $db->prepare( "UPDATE securitytokens SET securitytoken = :securitytoken WHERE identifier = :identifier" );
			$insert->execute( array( 'securitytoken' => sha1( $neuer_securitytoken ), 'identifier' => $identifier ) );
			setcookie( "identifier", $identifier, time() + ( 3600 * 24 * 365 ) ); //1 Jahr Gültigkeit
			setcookie( "securitytoken", $neuer_securitytoken, time() + ( 3600 * 24 * 365 ) ); // Valable 1 an

// Connectez-vous à l'utilisateur
			$_SESSION['userid'] = $securitytoken_row['user_id'];
		}
	}


	if ( ! isset( $_SESSION['userid'] ) ) {
		return false;
	}
	if ($_SESSION['site'] != "mpos") {
		return false;
	}


	$statement = $db->prepare( "SELECT * FROM users WHERE id = :id" );
	$result    = $statement->execute( array( 'id' => $_SESSION['userid'] ) );
	$user      = $statement->fetch();

	return $user;
}

function check_user() {
	if ( check_user_basic() != false && $_SESSION['site'] == "mpos" ) {
		return check_user_basic();
	} else {
		$_SESSION['msg'] = "Veuillez vous inscrire d'abord!";
		die( header( "Location: index.php" ) );
	}
}

/**
 * Renvoie vrai lorsque l'utilisateur est enregistré, sinon faux
 */
function is_checked_in() {
	if (isset($_SESSION['userid']) && $_SESSION['site'] == "mpos") {
		return true;
	} else {
		return false;
	}
}

/**
 * Renvoie une chaîne aléatoire
 */
function random_string() {
	if ( function_exists( 'openssl_random_pseudo_bytes' ) ) {
		$bytes = openssl_random_pseudo_bytes( 16 );
		$str   = bin2hex( $bytes );
	} else if ( function_exists( 'mcrypt_create_iv' ) ) {
		$bytes = mcrypt_create_iv( 16, MCRYPT_DEV_URANDOM );
		$str   = bin2hex( $bytes );
	} else {
		//Remplacez your_secret_string par une chaîne de votre choix (> 12 caractères)
		$str = md5( uniqid( 'your_secret_string', true ) );
	}

	return $str;
}

/**
 * Renvoie l'URL du site sans le nom du script
 */
function getSiteURL() {
	$protocol = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ) ? "https://" : "http://";

	return $protocol . $_SERVER['HTTP_HOST'] . dirname( $_SERVER['PHP_SELF'] ) . '/';
}

/**
 * Génère un message d'erreur et arrête l'exécution ultérieure du script.
 */
function error( $error_msg ) {
	include( "templates/header.inc.php" );
	include( "templates/error.inc.php" );
	include( "templates/footer.inc.php" );
	exit();
}

/**
* Sort les données de l'entreprise sous forme de tableau
*/
function getCompany() {
	global $db;
	//$sql = "SELECT * FROM company";
	//$company = $db->query($sql)->fetch();
	$statement = $db->prepare( "SELECT * FROM company WHERE id = :id" );
	$result    = $statement->execute( array( 'id' => 1 ) );
	$company   = $statement->fetch();
	return $company;
}
