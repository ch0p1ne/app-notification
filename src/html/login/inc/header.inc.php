<?php
@session_start();
require_once( "inc/config.inc.php" );
require_once( "inc/functions.inc.php" );
$site_color             = "amber";
$site_color_accent      = "red accent-4";
$site_color_text        = "amber-text";
$site_color_accent_text = "red-text text-accent-4";
if ( isset( $_SESSION['msg'] ) ) {
	$modal      = true;
	$modal_text = $_SESSION['msg'];
	unset( $_SESSION['msg']);
} else {
	$modal = false;
}


if ( is_checked_in() == true ) {
	$logged_in = true;
} else {
	$logged_in = false;
}

switch ( $logged_in ) {
	case true:
		$logging_link = "logout.php";
		$logging_imag = "clear";
		$logging_text = "Logout";
		$image_lock   = "lock_open";
		break;

	default:
		$logging_link = "login.php";
		$logging_imag = "check";
		$logging_text = "Login";
		$image_lock   = "lock";
		break;
}

if ( $modal ) {
	$toast = "M.toast({html: '$modal_text'})";
} else {
	$toast = "";
}



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= $site_title ?>Plateforme SENSERVICES.NET</title>

		<!--Favicons-->
		<link rel="shortcut icon" href="icons/favicon.ico">
		<link rel="icon" type="image/png" href="icons/favicon.png" sizes="48x48">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import custom.css-->
    <link type="text/css" rel="stylesheet" href="css/style.css"/>

    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection"/>

    <!-- Import JS -->
    <script src=" js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $(".button-collapse").sidenav();
            $('.materialboxed').materialbox();
            $('.tooltipped').tooltip();
						$('select').formSelect();
						$('.collapsible').collapsible();
						$('.modal').modal();
			<?= $toast?>
        });

    </script>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body>
<!--Header section-->
<header>
    <nav>
        <div class="nav-wrapper <?= $site_color ?>">
            <div class="container">
                <a href="index.php" class="brand-logo">Plateforme SENSERVICES.NET</a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                   
                </ul>
            </div>
        </div>
    </nav>
</header>
<!--main section-->
<main>
    <div class="container">
