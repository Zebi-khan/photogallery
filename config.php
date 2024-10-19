<?php

// Start session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Define absolute paths for easy navigation
define('BASE_URL', 'http://gallery.loc/');
define('LOGIN_PAGE', BASE_URL . 'login.php');
define('LOGOUT_PAGE', BASE_URL . 'logout.php');
define('ALBUMS_PAGE', BASE_URL . 'user/albums.php');
define('GALLERIES_PAGE', BASE_URL . 'user/gallery.php');