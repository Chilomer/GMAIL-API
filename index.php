<?php include('template.php');
	if(isset($_REQUEST['newMail'])){
		if($_REQUEST['newMail']){goToSendMail();}
	}
	if(isset($_REQUEST['responseMail'])){
		if($_REQUEST['responseMail']){goToSendMail($_REQUEST['responseMail']);}
	}
	if(isset($_REQUEST['viewMail'])){
		if($_REQUEST['viewMail']){goToReadMail($_REQUEST['viewMail']);}
	}

	$backPageToken = '';
	$inboxMessage[] = [
		'from' => 'No se encontraron resultados',
		'messageSubject' => ''
	];
	if( !isset($_SESSION['page']) ){
		$_SESSION['page'] = 1;
	}
	$messagesArrayOpt = definePageToken($_SESSION['page']);
	// echo json_encode ($messagesArrayOpt);
		try {
			$messagesList = $mail->users_messages->listUsersMessages('me', $messagesArrayOpt);
			$messages = $messagesList->getMessages();
			$_SESSION['pageToken' . ($_SESSION['page'] + 1)] = $messagesList['nextPageToken'];
		} catch (Exception $e) {
			if($e->getCode() === 401){
				$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
				header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
			}
		}

		if ($messages !== null){
			unset($inboxMessage);
		}
		foreach($messages as $dMessage){
			$messageId = $dMessage->id;
			$threadId = $dMessage->threadId;
			$gMessage = $mail->users_messages->get('me',$messageId,['format' => 'raw']);
			$dcMessage = base64url_decode($gMessage->getRaw());
			//echo json_encode($gMessage) . '<br>';
			$read = true;
			foreach($gMessage['labelIds'] as $label){
				if($label === 'UNREAD'){
					$read = false;
				}
			}

			if($dcMessage !== '' && $dcMessage !== null){
				$mimeDecode->setText($dcMessage);
				$mimeSubject = $mimeDecode->getHeader('subject');
				$from = $mimeDecode->getHeader('from');
				$text = $mimeDecode->getMessageBody('text');
				$html = $mimeDecode->getMessageBody('html');
				$htmlEmbedded = $mimeDecode->getMessageBody('htmlEmbedded'); //HTML Body included data
				
				$inboxMessage[] = [
					'from' => $from,
					'messageId' => $messageId,
					'threadId' => $threadId,
					'messageSubject' => $mimeSubject,
					'text' => $text,
					'read' => $read
				];
			}
		}	
?>
	<div class="container-fluid main-content">
		<div class="row set-padding-right">
			<div class="col">
				<form method="post" name="filter" role="form">
					<div class="input-group mb-3">
						<input type="text" class="form-control" id="filterInp" name="filterInp" placeholder="Buscar por remitente" aria-label="Buscar por remitente" aria-describedby="basic-addon2">
						<div class="input-group-append">
							<button class="btn btn-outline-primary" name="filter" value="filter" type="submit">Buscar</button>
						</div>
					</div>
				</form>
			</div>
			<form>
				<button type="submit" class="btn btn-outline-primary" name="newMail" value="newMail">Nuevo Correo</button>
			<form>
		</div>
		<ul class="list-group mail-list">
		<?php 
			foreach($inboxMessage as $msg){ 
		?>
			<li class="list-group-item mail-list-item <?php if(!$msg['read']) print 'mail-read'; else print ''; ?>">
				<div class="row">
					<div class="col-4">
						<h6>
							<?php print $msg['from']; 
								if(!$msg['read']) { ?> 
									<span class="badge badge-secondary">New</span>
								<?php } ?>
						</h6>
					</div>
					<div class="col-8">
						<?php print $msg['messageSubject'];
							if($msg['from'] !== 'No se encontraron resultados') {
						?>
						<form class="set-padding-left">
							<button type="submit" name="responseMail" value="<?php print $msg['messageId'] ?>" class="close set-color-blue" aria-label="Close">
								<i class="material-icons">reply</i>
							</button>
						<form>
						<form>
							<button style="margin-right:10px;" type="submit" name="viewMail" value="<?php print $msg['messageId'] ?>" class="close set-color-blue" aria-label="Close">
								<i class="material-icons">remove_red_eye</i>
							</button>
						<form>
							<?php } ?>
					</div>
				</div>
			</li>
			<?php } ?>
		</ul>
		<div class="row set-padding-right">
			<div class="col">
				<h6 style="margin:5px 0 0 5px"><?php print 'Página: ' . $_SESSION['page']; ?></h6>
				<button type="submit" class="btn btn-link"> </button>
			</div>
			<form>
				
				<?php if($_SESSION['page'] !==  1){ ?>
					<button type="submit" class="btn btn-link" name="previousPage" value="previousPage">Página anterior</button>
				<?php } ?>

				
				<button type="submit" class="btn btn-link" name="nextPage" value="nextPage">Siguiente página</button>
			<form>
				
		</div>

		</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script
		src="https://code.jquery.com/jquery-3.3.1.min.js"
		integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
		crossorigin="anonymous"></script>
	</body>
</html>

<?php 
	function definePageToken($page){
		if(isset($_POST['filter'])){
			$_SESSION['page'] = 1;
			$filter = 'in:inbox category:primary ' . $_POST['filterInp'];
			return ['maxResults' => 8, 'q' => $filter ];
		}

		if(isset($_REQUEST['previousPage'])){
			$_SESSION['page'] = $page - 1 ;
			if($_SESSION['page'] === 1){
				return ['maxResults' => 8, 'q' => 'in:inbox category:primary'];
			}
			return ['maxResults' => 8,'pageToken' => $_SESSION['pageToken' . $_SESSION['page']],  'q' => 'in:inbox category:primary'];
		} else if(isset($_REQUEST['nextPage'])) {
			$_SESSION['page'] = $page + 1 ;
			return ['maxResults' => 8,'pageToken' => $_SESSION['pageToken' . $_SESSION['page']],  'q' => 'in:inbox category:primary'];
		} else {
			$_SESSION['page'] = 1;
			return ['maxResults' => 8, 'q' => 'in:inbox category:primary'];
		}
	}
?>