<?php
    $fontSize = 20;
    $RandomStr = generateRandomString();
    $ResultStr = substr($RandomStr,0,5);
    $NewImage =imagecreatefromjpeg(BASE_PATH."/library/TK/Form/captcha/captcha-bg.jpg");
    $LineColor = imagecolorallocate($NewImage,250,156,50);
    imageline($NewImage,90,30,200,60,$LineColor);
    imageline($NewImage,1,40,220,0,$LineColor);
    
    $center = ((imagesx($NewImage)/2)-(strlen($ResultStr)));
    $stringArray = str_split($ResultStr);
    $fontPath = BASE_PATH."/library/TK/Form/captcha/arial.ttf";
    imagettftext($NewImage, $fontSize, getRandomRotation(), $center-40, 40, getRandomColor($NewImage), $fontPath, $stringArray[0]);
    imagettftext($NewImage, $fontSize, getRandomRotation(), $center-20, 40, getRandomColor($NewImage), $fontPath, $stringArray[1]);
    imagettftext($NewImage, $fontSize, getRandomRotation(), $center, 40, getRandomColor($NewImage), $fontPath, $stringArray[2]);
    imagettftext($NewImage, $fontSize, getRandomRotation(), $center+20, 40, getRandomColor($NewImage), $fontPath, $stringArray[3]);
    imagettftext($NewImage, $fontSize, getRandomRotation(), $center+40, 40, getRandomColor($NewImage), $fontPath, $stringArray[4]);
    $_SESSION['originalkey'] = $ResultStr;  //store the original coderesult in session variable

    header("Content-type: image/jpeg");
    imagejpeg($NewImage);
    
    
    function getRandomColor($NewImage){
        
        $number = rand(1,6);
        switch($number):
            case 1:
                return imagecolorallocate($NewImage, 255, 255, 255);
                break;
            case 2:
                return imagecolorallocate($NewImage, 255, 0, 0);
                break;
            case 3:
                return imagecolorallocate($NewImage, 51, 0, 255);
                break;
            case 4:
                return imagecolorallocate($NewImage, 34, 139, 34);
                break;
            case 5:
                return imagecolorallocate($NewImage, 255, 0, 255);
                break;
            case 6:
                return imagecolorallocate($NewImage, 0, 128, 0);
                break;
        endswitch;
    }
    
    function getRandomRotation(){
        
        $number = rand(-30,30);
        return $number;
    }

    function generateRandomString(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return md5($randomString);
    }
?>