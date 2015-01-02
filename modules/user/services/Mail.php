<?php
require BASE_PATH.'/library/PHPMailer/PHPMailerAutoload.php';

class MailService extends Service{
        
    public function __construct(){
    }
    
    public function getAllRallys(){
        return $this->userTable->findAll();
    }
    
    public function getUser($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->userTable->findOneBy($field,$id,$hydrationMode);
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
        $mail->FromName = 'Tomek CMS';
        $mail->addAddress($mailTo);
        $mail->addReplyTo('tomekvarts@o2.pl', 'Tomek CMS');

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $title;
        $mail->Body    = $content;

        return $mail->send();
    }
    
    public static function prepareRegistrationMail($token){
        $text = "Dziękujemy za rejestracje w Tomek CMS. Aby aktywować konto kliknij w link ";
        $text .= "<a href='http://ral.localhost/user/activate/token/".$token."'>http://ral.localhost/user/activate/token/".$token."</a>";
        
        return $text;
    }
    
     public static function prepareConfirmActivationMail(){
        $text = "Twoje konto w Tomek CMS zostało pomyślnie aktywowane. Przejdź na stronę ";
        $text .= "<a href='http://ral.localhost/user/login/'>http://ral.localhost/user/login/</a>";
        $text .= " aby się zalogować";
        return $text;
    }
    
}
?>
