<?php
session_start();
require_once( "inc/config.inc.php" );
require_once( "inc/functions.inc.php" );

//Überprüfe, dass der User eingeloggt ist
//Der Aufruf von check_user() muss in alle internen Seiten eingebaut sein
$user = check_user();
$company = getCompany();
$site_title = "Einstellungen";
include "inc/header.inc.php";

if ( isset( $_GET['save'] ) ) {
	$save = $_GET['save'];
	switch ($save) {
		case 'personal_data':
			$vorname  = trim( $_POST['vorname'] );
			$nachname = trim( $_POST['nachname'] );

			if ( $vorname == "" || $nachname == "" ) {
				$error_msg = "Bitte Vor- und Nachname ausfüllen.";
			} else {
				$statement = $connect->prepare( "UPDATE users SET vorname = :vorname, nachname = :nachname, updated_at=NOW() WHERE id = :userid" );
				$result    = $statement->execute( array(
					'vorname'  => $vorname,
					'nachname' => $nachname,
					'userid'   => $user['id']
				));
				$success_msg = "Daten erfolgreich gespeichert.";
			}
			break;

		case 'email':
			$passwort = $_POST['passwort'];
			$email    = trim( $_POST['email'] );
			$email2   = trim( $_POST['email2'] );

			if ( $email != $email2 ) {
				$error_msg = "Die eingegebenen E-Mail-Adressen stimmten nicht überein.";
			} else if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$error_msg = "Bitte eine gültige E-Mail-Adresse eingeben.";
			} else if ( ! password_verify( $passwort, $user['passwort'] ) ) {
				$error_msg = "Bitte korrektes Passwort eingeben.";
			} else {
				$statement = $connect->prepare( "UPDATE users SET email = :email WHERE id = :userid" );
				$result    = $statement->execute( array( 'email' => $email, 'userid' => $user['id'] ) );

				$success_msg = "E-Mail-Adresse erfolgreich gespeichert.";
			}
			break;

		case 'passwort':
			$passwortAlt  = $_POST['passwortAlt'];
			$passwortNeu  = trim( $_POST['passwortNeu'] );
			$passwortNeu2 = trim( $_POST['passwortNeu2'] );

			if ( $passwortNeu != $passwortNeu2 ) {
				$error_msg = "Die eingegebenen Passwörter stimmten nicht überein.";
			} else if ( $passwortNeu == "" ) {
				$error_msg = "Das Passwort darf nicht leer sein.";
			} else if ( ! password_verify( $passwortAlt, $user['passwort'] ) ) {
				$error_msg = "Bitte korrektes Passwort eingeben.";
			} else {
				$passwort_hash = password_hash( $passwortNeu, PASSWORD_DEFAULT );

				$statement = $connect->prepare( "UPDATE users SET passwort = :passwort WHERE id = :userid" );
				$result    = $statement->execute( array( 'passwort' => $passwort_hash, 'userid' => $user['id'] ) );

				$success_msg = "Passwort erfolgreich gespeichert.";
			}
			break;

		case 'name_g':
			$name_g = $_POST['name_g'];
			if ($name_g) {
				$statement = $connect->prepare("UPDATE company SET name = :name_g");
				$statement->execute(array('name_g' => $name_g));

				$success_msg = "Firmennamen erfolgreich gespeichert.";
			} else {
				$error_msg = "Bitte geben sie einen Firmennamen an.";
			}
			break;

		case 'adress':
			$street 	= $_POST['street'];
			$number 	= $_POST['number'];
			$postcode = $_POST['postcode'];
			$city 		= $_POST['city'];
			$state 		= $_POST['state'];
			if (! empty ( $street ) && ! empty ( $number) && ! empty ( $postcode) && ! empty ( $city) && ! empty ( $state ) ) {
				$statement = $connect->prepare("UPDATE company SET street = :street, number = :number, postcode = :postcode, city = :city, state = :state");
				$statement->execute(array('street' => $street, 'number' => $number, 'postcode' => $postcode, 'city' => $city, 'state' => $state));

				$success_msg = "Adresse erfolgreich gespeichert.";
			} else {
				$error_msg = "Bitte geben sie eine vollständige Adresse an.";
			}
			break;

		case 'contact':
			$email 		= $_POST['email'];
			$tel 		= $_POST['tel'];
			if (! empty ( $email ) && ! empty ( $tel) ) {
				$statement = $connect->prepare("UPDATE company SET email = :email, tel = :tel");
				$statement->execute(array('email' => $email, 'tel' => $tel));

				$success_msg = "Kontaktdaten erfolgreich gespeichert.";
			} else {
				$error_msg = "Bitte geben sie vollständige Daten an.";
			}
			break;

		case 'logo':
			$logo = $_POST['logo'];
			if ($logo) {
				$statement = $connect->prepare("UPDATE company SET logo = :logo");
				$statement->execute(array('logo' => $logo));

				$success_msg = "Pfad zum Logo erfolgreich gespeichert.";
			} else {
				$error_msg = "Bitte geben sie einen gültigen Pfad an.";
			}
			break;
		default:
			// code...
			break;
	}
}

$user = check_user();
$company = getCompany();
?>


<h1 class="<?php echo $site_color_accent_text; ?>">Einstellungen</h1>
<?php
if ( isset( $success_msg ) && ! empty( $success_msg ) ):
	?>
<script>
		M.toast({html: '<i class="material-icons">check</i> <?=$success_msg?>'});
</script>
<?php
endif;
?>

<?php
if ( isset( $error_msg ) && ! empty( $error_msg ) ):
	?>
	<script>
			M.toast({html: '<i class="material-icons">error_outline</i> <?=$error_msg?>'});
	</script>
<?php
endif;
?>
<div class="row">
	<div class="col s12 m4">
		<h3>Persönliche Einstellungen</h3>
		<p>Ändern Sie Ihren Namen, Ihr Passwort und Ihre E-Mail-Adresse</p>
	</div>
	<div class="col s12 m8">
		<ul class="collapsible">
    	<li>
      	<div class="collapsible-header"><i class="material-icons">account_circle</i>Name</div>
      	<div class="collapsible-body">
					<form action="?save=personal_data" method="post" class="col s12">
	            <p>Zum Änderen ihres Namens geben sie bitte den neuen, sowie ihre E-Mail-Adresse ein.</p>

	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputVorname" name="vorname" type="text"
	                       value="<?php echo htmlentities( $user['vorname'] ); ?>" required>
	                <label for="inputVorname">Vorname</label>
	            </div>

	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputNachname" name="nachname" type="text"
	                       value="<?php echo htmlentities( $user['nachname'] ); ?>" required>
	                <label for="inputNachname">Nachname</label>
	            </div>
							<div class="input-field col s12 m6">
	                <input class="validate" id="inputEmail" name="email" type="email"
	                       value="<?php echo htmlentities( $user['email'] ); ?>" required>
	                <label for="inputEmail">E-Mail</label>
	            </div>

	            <button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 m6 btn-large">Speichern</button>
	        </form>
					&nbsp;
				</div>
    	</li>
			<li>
				<div class="collapsible-header"><i class="material-icons">email</i>E-Mail-Adresse</div>
      	<div class="collapsible-body">
					<form action="?save=email" method="post" class="col s12">
	            <p>Zum Ändern Ihrer E-Mail-Adresse geben Sie bitte Ihr aktuelles Passwort sowie die neue E-Mail-Adresse
	                ein.</p>

	            <div class="input-field col s12">
	                <input class="validate" id="inputPasswort" name="passwort" type="password" required>
	                <label for="inputPasswort">Passwort</label>
	            </div>

	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputEmail" name="email" type="email"
	                       value="<?php echo htmlentities( $user['email'] ); ?>" required>
	                <label for="inputEmail">E-Mail</label>
	            </div>

	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputEmail2" name="email2" type="email" required>
	                <label for="inputEmail2">E-Mail (wiederholen)</label>
	            </div>

	           <button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Speichern</button>
	        </form>
					&nbsp;
				</div>
			</li>
			<li>
				<div class="collapsible-header"><i class="material-icons">security</i>Passwort</div>
      	<div class="collapsible-body">
					<form action="?save=passwort" method="post" class="col s12">
	            <p>Zum Änderen Ihres Passworts geben Sie bitte Ihr aktuelles Passwort sowie das neue Passwort ein.</p>

	            <div class="input-field col s12">
	                <input class="validate" id="inputPasswort" name="passwortAlt" type="password" required>
	                <label for="inputPasswort">Altes Passwort</label>
	            </div>

	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputPasswortNeu" name="passwortNeu" type="password" required>
	                <label for="inputPasswortNeu">Neues Passwort</label>
	            </div>


	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputPasswortNeu2" name="passwortNeu2" type="password" required>
	                <label for="inputPasswortNeu2">Neues Passwort (wiederholen)</label>
	            </div>

	            <button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Speichern</button>
	        </form>
					&nbsp;
				</div>
			</li>
		</ul>
	</div>
</div>
<div class="row">
	<div class="col s12 m4">
		<h3>Einstellungen des Betriebes</h3>
		<p>Ändern Sie Namen, Adresse und weiter Daten ihres Bertriebes</p>
	</div>
	<div class="col s12 m8">
		<ul class="collapsible">
    <li>
      <div class="collapsible-header"><i class="material-icons">store</i>Name des Betriebes</div>
      <div class="collapsible-body">
				<form action="?save=name_g" method="post" class="col s12">
					<div class="input-field col s12">
							<input class="validate" id="id" name="name_g" type="text" required value="<?=$company['name']?>">
							<label for="id">Firmenname</label>
					</div>
					<button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Speichern</button>
				</form>
				&nbsp;
			</div>
    </li>
		<li>
      <div class="collapsible-header"><i class="material-icons">location_on</i>Adresse</div>
      <div class="collapsible-body">
				<form action="?save=adress" method="post" class="col s12">
					<div class="input-field col s10">
							<input class="validate" id="id" name="street" type="text" required value="<?=$company['street']?>">
							<label for="id">Straße</label>
					</div>
					<div class="input-field col s2">
							<input class="validate" id="id" name="number" type="text" required value="<?=$company['number']?>">
							<label for="id">Hausnummer</label>
					</div>
					<div class="input-field col s4">
							<input class="validate" id="id" name="postcode" type="text" required value="<?=$company['postcode']?>">
							<label for="id">Postleitzahl</label>
					</div>
					<div class="input-field col s4">
							<input class="validate" id="id" name="city" type="text" required value="<?=$company['city']?>">
							<label for="id">Stadt</label>
					</div>
					<div class="input-field col s4">
							<input class="validate" id="id" name="state" type="text" required value="<?=$company['state']?>">
							<label for="id">Land</label>
					</div>
					<button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Speichern</button>
				</form>
				&nbsp;
			</div>
    </li>
		<li>
      <div class="collapsible-header"><i class="material-icons">contact_mail</i>Kontakt</div>
      <div class="collapsible-body">
				<form action="?save=contact" method="post" class="col s12">
					<div class="input-field col s12">
							<input class="validate" id="id" name="email" type="email" required value="<?=$company['email']?>">
							<label for="id">E-Mail-Adresse</label>
					</div>
					<div class="input-field col s12">
							<input class="validate" id="id" name="tel" type="tel" required value="<?=$company['tel']?>">
							<label for="id">Telefonnummer</label>
					</div>
					<button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Speichern</button>
				</form>
				&nbsp;
			</div>
    </li>
		<li>
      <div class="collapsible-header"><i class="material-icons">image</i>Logo</div>
      <div class="collapsible-body">
				<form action="?save=logo" method="post" class="col s12">
					<div class="input-field col s12">
							<input class="validate" id="id" name="logo" type="text" required value="<?=$company['logo']?>">
							<label for="id">Pfad zum Logo</label>
					</div>
					<button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Speichern</button>
				</form>
				&nbsp;
			</div>
    </li>
	</div>
</div>
<?php
include( "inc/footer.inc.php" )
?>
