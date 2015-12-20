<?php

require BASE_PATH . '/library/PHPMailer/PHPMailerAutoload.php';

class MailService extends Service {

    private static $instance = NULL;

    static public function getInstance() {
        if (self::$instance === NULL)
            self::$instance = new MailService();
        return self::$instance;
    }

    public function __construct() {
        
    }

    public function sendMail($mailTo, $title, $content) {
        echo $content;exit;
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
        $mail->Body = $content;

        return $mail->send();
    }

    public static function prepareRegistrationMail($token, $username) {


//        $text = "<h1 style='font-size:20px;padding:20px 10px 0px 20px'>Welcome to FastRally </h1>";
        $text = View::getInstance()->translate("Dear")." ". $username . ",<br /><br /> ";
        $text .= View::getInstance()->translate("Thank you for your registration in FastRally.")."<br />";
        $text .= View::getInstance()->translate("To activate your account please click the link below")."<br /> ".View::getInstance()->translate("or paste the url in your browser.")."<br />";
        $text .= "<a style='color:#f3f3f3;' href='http://fastrally.eu/user/activate/" . $token . "'>http://fastrally.eu/user/activate/" . $token . "</a><br />";
        $text .= View::getInstance()->translate("Let your journey in world of Fast Rally begin.")."<br /><br />";
        $text .= View::getInstance()->translate("FastRally Team");

        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');

        $content = self::getTemplate($text, $facebookUrl, $twitterUrl);
        return $content;
    }

    private static function getTemplate($text, $facebookUrl, $twitterUrl) {


        if (isset($GLOBALS['lang']) && $GLOBALS['lang'] == 'pl') {

            ob_start();

            include(BASE_PATH . "/modules/user/views/mail/template-pl.phtml");
            $content = ob_get_contents();

            ob_end_clean();
        } else {
            ob_start();

            include(BASE_PATH . "/modules/user/views/mail/template.phtml");
            $content = ob_get_contents();

            ob_end_clean();
        }

        return $content;
    }

    public static function prepareSupportMail($user, $content) {
        $text = "You have new enquiry by " . $user['username'] . ",<br /><br />";
        $text .= $content;
        $text .= "<br /><br />";
        $text .= "Enquiry was received on " . date("d/m/Y H:i");

        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');

        $content = self::getTemplate($text, $facebookUrl, $twitterUrl);
        return $content;
    }

    public static function prepareConfirmActivationMail($username) {
        $text = View::getInstance()->translate("Dear")." " . $username . ",<br /><br />";
        $text .= View::getInstance()->translate("Congratulations! Your account in FastRally is now active")." <br />";
        $text .= View::getInstance()->translate("You can now fully enjoy the world of FastRally.")."<br />";
        $text .= View::getInstance()->translate("Log in with the link below and compete with other drivers on FastRally routes.")."<br />";
        $text .= "<a style='color:#f3f3f3' href='http://fastrally.eu/user/login/'>http://fastrally.eu/user/login/</a><br />";
        $text .= "<br />".View::getInstance()->translate("Kind regards").", <br />";
        $text .= View::getInstance()->translate("FastRally Team");


        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');

        $content = self::getTemplate($text, $facebookUrl, $twitterUrl);


        return $content;
    }

    public static function prepareInvitationMail($email, $user, $invite) {
        $text = View::getInstance()->translate("Hello").", <br /><br />";
        $text .= View::getInstance()->translate("Your friend")." " . $user['username'] ." ". View::getInstance()->translate("has invited you to play FastRally.")."<br />";
        $text .= View::getInstance()->translate("Join FastRally today, manage your very own rally team and show your friend who is a better manager.")." <br />";
        $text .= "<a style='color:#f3f3f3' href='http://fastrally.eu/user/register?ref=" . $invite['id'] . "&email=" . $email . "'>http://fastrally.eu/user/register?ref=" . $invite['id'] . "&email=" . $email . "</a><br />";
        $text .= "<br />".View::getInstance()->translate("Kind regards").", <br />";
        $text .= View::getInstance()->translate("FastRally Team");

        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');

        $content = self::getTemplate($text, $facebookUrl, $twitterUrl);

        return $content;
    }

    public static function prepareReminderMail($user, $token) {
        $text = View::getInstance()->translate("Hello")." " . $user['username'] . ", <br /><br />";
        $text .= View::getInstance()->translate("You requested new password to your FastRally account.")."<br />";
        $text .= View::getInstance()->translate("To receive new password click the link below.")." <br />";
        $text .= "<a style='color:#f3f3f3' href='http://fastrally.eu/new-password?id=" . $token . "&info=" . $user->get('id') . "'>http://fastrally.eu/new-password?id=" . $token . "&info=" . $user->get('id') . "</a><br /><br />";
        $text .= View::getInstance()->translate("If you did not request new password, please ignore this email.")." <br />";
        $text .= "<br />".View::getInstance()->translate("Kind regards").", <br />";
        $text .= View::getInstance()->translate("FastRally Team");

        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');

        $content = self::getTemplate($text, $facebookUrl, $twitterUrl);

        return $content;
    }

    public static function prepareNewPasswordMail($user, $password) {
        $text =  View::getInstance()->translate("Hello")." " . $user['username'] . ", <br /><br />";
        $text .=  View::getInstance()->translate("Your new password to your FastRally account is")."<br /><br />";
        $text .= "<strong>" . $password . "</strong>.<br /><br />";
        $text .=  View::getInstance()->translate("You can now")." <a style='color:#f3f3f3' href='http://fastrally.eu'>".View::getInstance()->translate("log in")."</a> ". View::getInstance()->translate("using your new password.")." <br /><br />";
        $text .= "<strong>".View::getInstance()->translate("Please change your password immidately after log in")."</strong><br />";
        $text .= "<br />".View::getInstance()->translate("Kind regards").", <br />";
        $text .=  View::getInstance()->translate("FastRally Team");

        $facebookUrl = View::getInstance()->getSetting('facebookUrl');
        $twitterUrl = View::getInstance()->getSetting('twitterUrl');

        $content = self::getTemplate($text, $facebookUrl, $twitterUrl);

        return $content;
    }

}

?>
