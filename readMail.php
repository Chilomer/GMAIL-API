<?php 
    include('template.php');
    if(isset($_REQUEST['backInbox'])){
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'];
		header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
    if(isset($_REQUEST['responseMail'])){
        if($_REQUEST['responseMail']){goToSendMail($_REQUEST['responseMail']);}
    }
    try {

    $notice = '';
    $htmlEmbedded = '';
    $addresses = '';
    $to = '';
    $body = '';
    $subject = '';
    $messageId = '';
    if(isset($_GET["id"]) && $_GET["id"] !== '' && $_GET["id"] !== null){
        // get message
        $messageId = $_GET["id"];

        $gMessage = $mail->users_messages->get('me',$messageId,['format' => 'raw']);
        $messageDetails = $gMessage->getPayload();
        echo $messageDetails['parts'];
        

        $threadId = $gMessage->threadId;
        //get message thread
        $threads = $mail->users_threads->get('me', $threadId)->getMessages();
        
        foreach($threads as $thread){
            $threadId = $thread['id'];

            $gMessage = $mail->users_messages->get('me',$threadId,['format' => 'raw']);
            $dcMessage = base64url_decode($gMessage->getRaw());

            $messageDetals = $gMessage->getPayload();
            echo json_encode($messageDetals) . '<br>';
            //echo json_encode($messageDetals['parts']) . '<br>';
            // foreach ($messageDetals['parts'] as $value) {
            //     if (!isset($value['body']['data'])) {
            //         array_push($files, $value['partId']);
            //     }
            // }
            //echo json_encode($gMessage) . '<br>';
    
             if($dcMessage !== '' && $dcMessage !== null){
                $mimeDecode->setText($dcMessage);
                $mimeSubject = $mimeDecode->getHeader('subject');
                $from = $mimeDecode->getHeader('from');
                $text = $mimeDecode->getMessageBody('text');
                $html = $mimeDecode->getMessageBody('html');
                $htmlEmbedded = $mimeDecode->getMessageBody('htmlEmbedded'); //HTML Body included data
                $addresses = $mimeDecode->getAddresses('from');
                $to = $addresses[0]['address'];
                $body = $text;
                $subject = $mimeSubject;
               

                $attach_dir = 'C:/Users/Toshiba P55/Documents/compart/atachments'; 	// Be sure to include the trailing slash
                $include_inline = true;  			// Optional argument to include inline attachments (default: true)
                $mimeDecode->saveAttachments($attach_dir, [$include_inline]);

                $attachments = $mimeDecode->getAttachments([$include_inline]);
                if (count($attachments) > 0) {
                    foreach ($attachments as $attachment) {
                        // $headers = $attachment->getHeaders();
                        // echo 'id : ' . $headers['x-attachment-id'] . '<br />';
                        // echo 'Filename : '.$attachment->getFilename().'<br />'; // logo.jpg
                        // echo 'Filesize : '.filesize($attach_dir.$attachment->getFilename()).'<br />'; // 1000
                        // echo 'Filetype : '.$attachment->getContentType().'<br />'; // image/jpeg
                        // echo 'MIME part string : '.$attachment->getMimePartStr().'<br />'; // (the whole MIME part of the attachment)
                    }
                }


                $threadMessages[] = [
					'messageId' => $messageId,
					'threadId' => $threadId,
                    'messageSubject' => $mimeSubject,
                    'htmlEmbedded' => $htmlEmbedded,
					'text' => $text
                ];
                $attachmentObj = $mail->users_messages_attachments->get('me', $messageId, 'f_jcbidu0p2');
                $data = $attachmentObj->getData(); //Get data from attachment object
                echo json_encode($data);
            }
        }
    }
} catch (Exception $e) {
    if($e->getCode() === 401){
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
}
?>      
            
            <div class="row set-padding-right">
                <div class="col"></div>
                <form>
                    <button type="submit" class="btn btn-outline-primary" name="backInbox" value="backInbox">
                        <i class="material-icons">arrow_back</i>                    
                    </button>
                <form>
                <form action="readMail.php">
                    <button type="submit" class="btn btn-outline-primary" name="responseMail" value="<?php print $messageId ?>">
                        <i class="material-icons">reply</i>
                    </button>
                </form>
            </div>
            <div class="form-group">
                <input type="email"
                    readonly
                    class="form-control" 
                    value="<?php print $to ?>" 
                    id="to" name="to" 
                    aria-describedby="email" 
                    placeholder="Enter email">
            </div>
            <div class="form-group">
                <input type="text" readonly class="form-control" value="<?php print $subject ?>" id="subject" name="subject" aria-describedby="emailHelp" placeholder="Asunto">
            </div>
            <?php
                foreach($threadMessages as $msg){
            ?>
                 <div class="row set-padding-right separator">
                </div>
            <?php
                print $msg['htmlEmbedded'];
                //print json_encode($msg);
            } ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    </body>
</html>