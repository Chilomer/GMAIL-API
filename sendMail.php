<?php 
    include('template.php');
    if(isset($_REQUEST['backInbox'])){
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'];
		header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
    $notice = '';
    $htmlEmbedded = '';
    $addresses = '';
    $to = '';
    $body = '';
    $subject = '';
    if(isset($_GET["id"]) && $_GET["id"] !== '' && $_GET["id"] !== null){
        // get message
        $messageId = $_GET["id"];

        $gMessage = $mail->users_messages->get('me',$messageId,['format' => 'raw']);
        $threadId = $gMessage->threadId;
        //get message thread
        $threads = $mail->users_threads->get('me', $threadId)->getMessages();
        
        foreach($threads as $thread){
            $threadId = $thread['id'];

            $gMessage = $mail->users_messages->get('me',$threadId,['format' => 'raw']);
            $dcMessage = base64url_decode($gMessage->getRaw());
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
                // echo json_encode($addresses[0]['address']);

                $threadMessages[] = [
					'messageId' => $messageId,
					'threadId' => $threadId,
                    'messageSubject' => $mimeSubject,
                    'htmlEmbedded' => $htmlEmbedded,
					'text' => $text
                ];
            }
        }
    }
    if(isset($_REQUEST['sendMail'])){
        echo 1;
        if($_REQUEST['sendMail']){
            echo 2;
            $to = $_REQUEST['to'];
            $bcc = $_REQUEST['bcc'];
            $cc = $_REQUEST['cc'];
            $body = $_REQUEST['message'];
            $subject = $_REQUEST['subject'];

            $mime->addTo($to);
            $mime->addBcc($bcc);
            $mime->addCc($cc);
            $mime->setTXTBody($body);
            $mime->setHTMLBody($body);
            $mime->setSubject($subject);
            $message_body = $mime->getMessage();

            $encoded_message = base64url_encode($message_body);

            // Gmail Message Body
            $message = new Google_Service_Gmail_Message();
            $message->setRaw($encoded_message);
            $message->threadId = $threadId;
            // Send the Email
            //echo json_encode($message);
            $email = $mail->users_messages->send('me',$message);
            if($email->getId()){
?>
                <script>    
                    alert('Email Sent successfully!');
                </script>
<?php
                $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'];
                header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
            } else {
?>
            <script>    
                alert('Ocurrio un error!');
            </script>
<?php
            }
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
        </div>
        <form role="form" name="sendMail" method="request" action="sendMail">
            <div class="form-group">
                <input type="email"
                    class="form-control" 
                    value="<?php print $to ?>" 
                    id="to" name="to" 
                    aria-describedby="email" 
                    placeholder="Enter email">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" id="cc" name="cc" aria-describedby="emailHelp" placeholder="CC">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" id="bcc" name="bcc" aria-describedby="emailHelp" placeholder="CCO">
            </div>
            <div class="form-group">
                <input type="text" <?php isset($_GET["id"]) && $_GET["id"] !== '' && $_GET["id"] !== null ? print 'readonly' : print '' ?> class="form-control" value="<?php print $subject ?>" id="subject" name="subject" aria-describedby="emailHelp" placeholder="Asunto">
            </div>
            <div class="form-group">
                <textarea class="form-control" aria-label="With textarea" id="message" name="message" placeholder="..." rows="8"></textarea>
            </div>
            <div class="row set-padding-right set-margin-bottom">
                <div class="col"></div>
                <button type="submit" name="sendMail" value="sendMail" class="btn btn-outline-primary">
                   Enviar
                </button>
            </div>
            <?php
            if(isset($threadMessages)){
                foreach($threadMessages as $msg){
            ?>
                 <div class="row set-padding-right separator">
                </div>
            <?php
                print $msg['htmlEmbedded'];
                //print json_encode($msg);
            } }?>
        </form>
        <!-- <form>
            <div class="row set-padding-right">
                <div class="col"></div>
                <button type="submit" class="btn btn-outline-primary" name="setReading" value="setReading">Responder</button>
            </div>
        <form> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
        <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
        <script>tinymce.init({ selector:'textarea' });</script>
    </body>
</html>
