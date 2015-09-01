<?php
require BASE_PATH.'/library/PHPMailer/PHPMailerAutoload.php';

class MailService extends Service{
        
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new MailService();
       return self::$instance;
    }
    
    public function __construct(){
    }
    
    
    
    public function sendMail($mailTo, $title, $content){
        $mail = new PHPMailer;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'poczta.o2.pl';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'tomekvarts@o2.pl';                 // SMTP username
        $mail->Password = '@#VArts001';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to
        $mail->CharSet = "UTF-8";
        $mail->From = 'tomekvarts@o2.pl';
        $mail->FromName = 'FastRally';
        $mail->addAddress($mailTo);
        $mail->addReplyTo('tomekvarts@o2.pl', 'FastRally');
        $mail->addBCC('kardi31@o2.pl');
        
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $title;
        $mail->Body    = $content;

        return $mail->send();
    }
    
    public static function prepareRegistrationMail($token,$username){
        $text = "Dear ".$username.",<br />";
        $text .= "Thank you for your registration in FastRally.<br />";
        $text .= "Thank you for your registration in FastRally.<br />";
        $text .= "To activate your account please click the link below or paste the url in your browser.<br />";
        $text .= "<a style='color:#f3f3f3;' href='http://".$_SERVER['SERVER_NAME']."/user/activate/".$token."'>http://".$_SERVER['SERVER_NAME']."/user/activate/".$token."</a><br />";
        $text .= "Let your journey in world of Fast Rally begin.<br /><br />";
        $text .= "FastRally Team";
        
        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');
        ob_start();
        include(BASE_PATH."/modules/user/views/mail/template.phtml");
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
     public static function prepareConfirmActivationMail($username){
        $text = "Dear ".$username.",<br />";
        $text .= "Congratulations! Your account in FastRally is now active <br />";
        $text .= "You can now fully enjoy the world of FastRally.<br />";
        $text .= "Log in with the link below and compete with other drivers on FastRally routes.<br />";
        $text .= "<a style='color:#f3f3f3' href='http://".$_SERVER['SERVER_NAME']."/user/login/'>http://".$_SERVER['SERVER_NAME']."/user/login/</a><br />";
        $text .= "<br />Kind regards, <br />";
        $text .= "FastRally Team";
        
        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');
        ob_start();
        include(BASE_PATH."/modules/user/views/mail/template.phtml");
        $content = ob_get_contents();
        ob_end_clean();
        
        
        return $content;
    }
    
    public static function prepareInvitationMail($email,$user,$invite){
        $text = "Hello, <br />";
        $text .= "Your friend ".$user['username']." has invited you to play FastRally.<br />";
        $text .= "Join FastRally today, manage your very own rally team and show your friend who is a better manager. <br />";
        $text .= "<a style='color:#f3f3f3' href='http://".$_SERVER['SERVER_NAME']."/user/register?ref=".$invite['id']."&email=".$email."'>http://".$_SERVER['SERVER_NAME']."/user/register?ref=".$invite['id']."&email=".$email."</a><br />";
        $text .= "<br />Kind regards, <br />";
        $text .= "FastRally Team";
        
        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');
        ob_start();
        include(BASE_PATH."/modules/user/views/mail/template.phtml");
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
    
    public static function prepareReminderMail($user,$token){
        $text = "Hello ".$user['username'].", <br />";
        $text .= "You requested new password to your FastRally account.<br />";
        $text .= "To receive new password click the link below. <br />";
        $text .= "<a style='color:#f3f3f3' href='http://".$_SERVER['SERVER_NAME']."/new-password?id=".$token."&info=".$user->get('id')."'>http://".$_SERVER['SERVER_NAME']."/new-password?id=".$token."&info=".$user->get('id')."</a><br /><br />";
        $text .= "If you did not request new password, please ignore this email. <br />";
        $text .= "<br />Kind regards, <br />";
        $text .= "FastRally Team";
        
        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');
        ob_start();
        include(BASE_PATH."/modules/user/views/mail/template.phtml");
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
    
    public static function prepareNewPasswordMail($user,$password){
        $text = "Hello ".$user['username'].", <br />";
        $text .= "Your new password to your FastRally account is.<br /><br />";
        $text .= "<strong>".$password."</strong>.<br /><br />";
        $text .= "You can now <a style='color:#f3f3f3' href='http://".$_SERVER['SERVER_NAME']."'>log in</a> using your new password. <br /><br />";
        $text .= "<strong>Please change your password immidately after log in</strong><br />";
        $text .= "<br />Kind regards, <br />";
        $text .= "FastRally Team";
        
        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');
        ob_start();
        include(BASE_PATH."/modules/user/views/mail/template.phtml");
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
    
}
?>
