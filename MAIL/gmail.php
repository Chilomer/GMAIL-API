<?php
	require_once __DIR__.'/vendor/autoload.php';
	require 'helpers/helpers.php';

	session_start();
	// session_unset();
	// session_destroy();
	
	$client = new Google_Client();
	$client->setAuthConfig('client_secrets.json');
	$client->addScope('https://mail.google.com/');
	$client->addScope('https://www.googleapis.com/auth/gmail.settings.sharing');
	$client->addScope('https://www.googleapis.com/auth/gmail.settings.basic');
	$client->setAccessType("offline");
	$mimeDecode = new PhpMimeMailParser\Parser();
	
	if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		$client->setAccessToken($_SESSION['access_token']);
		$client->getRefreshToken($_SESSION['refresh_token']);
		if(isset($_REQUEST['goOut'])){
			$client->revokeToken($_SESSION['access_token']);
			unset($_SESSION['access_token']);
			unset($_SESSION['refresh_token']);
			$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
			header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		}
		$mail = new Google_Service_Gmail($client);
		$mime = new Mail_mime();
		// echo json_encode($_SESSION['access_token']);
		$user = 'me';
		// $results = $mail->users_labels->listUsersLabels($user);
	}else {
		$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
		header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
	}

	function goToSendMail($id = ''){
		$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/sendMail.php?id=' . $id;
		header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
	}
	function goToReadMail($id = ''){
		$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/ReadMail.php?id=' . $id;
		header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
	}
?>