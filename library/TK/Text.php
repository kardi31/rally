<?php
 
class TK_Text{
         
    const LETTERS = 'letters';
    const WORDS = 'words';
    const PARAGRAPHS = 'paragraphs';
    const BLOCK = 'block';
    
    public function __construct(){
       
    }
    
    public static function createUniqueToken($string = null, $algo = 'md5') {
        $tmp = (null == $string) ? uniqid() . microtime() : $string;
        return hash($algo, $tmp);
    }
    
    
    public static function encode($password, $salt = '', $algo = 'md5') {
        return hash($algo, $password . $salt);
    }
    
    public static function timeFormat($time, $outputFormat, $inputFormat = 'Y-m-d H:i:s') {
        if(!$time) 
            return false;
        if($dateTime = DateTime::createFromFormat($inputFormat, $time))
            return $dateTime->format($outputFormat);
    }
    
    
    
    public static function createUniqueTableSlug($table, $string, $id = 0, $toLower = true, $space = '-') {
        $slug = self::createSlug($string, $toLower, $space);
        $q = Doctrine_Query::create()
                ->select('x.id')
                ->from($table . ' x')
                ->where('x.id != ? AND (x.slug = ?)')
                ;

        if($q->count(array((int) $id, $slug)) == 0) {
            return $slug;
        }
        
        $counter = 1;
        $tmpSlug = $slug;
        do {
            $tmpSlug = ($counter > 0) ? $slug . '-' . $counter : $slug;
            $counter++;
        } while(($q->count(array((int) $id, $tmpSlug)) > 0));
        return $tmpSlug;
    }
    
    public static function createSlug($string, $toLower = true, $space = '-') {
		$chars=array(
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
		chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
		chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
		chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
		chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
		chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
		chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
		chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
		chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
		chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
		chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
		chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
		chr(195).chr(191) => 'y',
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R', chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R', chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R', chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S', chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S', chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S', chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
		chr(226).chr(130).chr(172) => 'E',
		chr(194).chr(163) => '',
			        ' ' => $space
		);
		$string = strtr($string, $chars);
        
        $patterns = array('/[^a-zA-Z0-9-_\.]/', '/(?:[.](?!(doc|pdf|jpg|gif|png|jpeg|mpg|mpeg|avi|mov|mp4|DOC|JPG|GIF|PNG|JPEG|MPG|MPEG|AVI|MOV|MP4)$))/', '/(-){2,}/', '/^(-)+|(-)+$/');
        $replacements = array('-', '', '-', '');
		
        $string = preg_replace($patterns, $replacements, $string);
		if ($toLower) {
			return strtolower($string);
		}
	}
	
	
	
    public static function float_rand($min, $max, $round=false){
	//validate input
	$randomfloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
	if($round)
	    $randomfloat = round($randomfloat,$round);

	return $randomfloat;
    }
    
    public static function convertViewToActionName($viewName){
        $parts = explode('-',$viewName);
        $actionName = "";
        foreach($parts as $part):
            $part = ucfirst($part);
            $actionName .= $part;
        endforeach;
        $actionName = lcfirst($actionName);
        
        return $actionName;
    }
    
    public static function truncate($str, $limit = 60, $type = 'letters', $delim = '...', $force = true) {
        $result = $str;
        $tmp = trim(strip_tags($str));
        $len = strlen($tmp);
        if(strlen($tmp) > $limit) {
            switch($type) {
                case self::LETTERS:
                    $result = (true ==$force) ? trim(substr($tmp, 0, $limit)) . $delim : trim(substr($tmp, 0, strrpos(substr($tmp, 0, $limit), ' '))) . $delim;
                    break;
                case self::WORDS:
                    preg_match('/(.{' . $limit . '}.*?)\b/', $tmp, $matches);
                    if(isset($matches[1])) {
                        $result = trim($matches[1]) . $delim;
                    }
                    break;
                case self::PARAGRAPHS:
                    preg_match_all('/(<p(>|\s+[^>]*>).*?<\/p>)/i', $str, $match);
                    if(count($match[0]) == 0) {
                        return $str;
                    }
                    $paragraphs = array_slice($match[0], 0, $limit);
                    return implode('', $paragraphs);
                    break;
                case self::BLOCK:
                    preg_match_all('/(<(p|div|h1|h2|h3|h4|h5|h6)(>|\s+[^>]*>).*?<\/(p|div|h1|h2|h3|h4|h5|h6)>)/i', $str, $match);
                    if(count($match[0]) == 0) {
                        return $str;
                    }
                    $paragraphs = array_slice($match[0], 0, $limit);
                    return implode('', $paragraphs);
                    break;
            }
            
        }
        return $result;
    }
    
    public static function addOrdinalNumberSuffix($num){
        if (!in_array(($num % 100),array(11,12,13))){
          switch ($num % 10) {
            // Handle 1st, 2nd, 3rd
            case 1:  return $num.'st';
            case 2:  return $num.'nd';
            case 3:  return $num.'rd';
          }
        }
        return $num.'th';
    }
    
    public static function dotDash($word){
        if(strpos($word,'.')!==false){
            return str_replace('.','-',$word);
        }
        elseif(strpos($word,'-')!==false){
            return str_replace('-','.',$word);
        }
    }
    
    public static function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
    /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
        (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
    */
    if (!$using_timestamps) {
        $datefrom = strtotime($datefrom, 0);
        $dateto = strtotime($dateto, 0);
    }
    $difference = $dateto - $datefrom; // Difference in seconds
    switch($interval) {
     
    case 'yyyy': // Number of full years
        $years_difference = floor($difference / 31536000);
        if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
            $years_difference--;
        }
        if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
            $years_difference++;
        }
        $datediff = $years_difference;
        break;
    case "q": // Number of full quarters
        $quarters_difference = floor($difference / 8035200);
        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
            $months_difference++;
        }
        $quarters_difference--;
        $datediff = $quarters_difference;
        break;
    case "m": // Number of full months
        $months_difference = floor($difference / 2678400);
        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
            $months_difference++;
        }
        $months_difference--;
        $datediff = $months_difference;
        break;
    case 'y': // Difference between day numbers
        $datediff = date("z", $dateto) - date("z", $datefrom);
        break;
    case "d": // Number of full days
        $datediff = floor($difference / 86400);
        break;
    case "w": // Number of full weekdays
        $days_difference = floor($difference / 86400);
        $weeks_difference = floor($days_difference / 7); // Complete weeks
        $first_day = date("w", $datefrom);
        $days_remainder = floor($days_difference % 7);
        $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
        if ($odd_days > 7) { // Sunday
            $days_remainder--;
        }
        if ($odd_days > 6) { // Saturday
            $days_remainder--;
        }
        $datediff = ($weeks_difference * 5) + $days_remainder;
        break;
    case "ww": // Number of full weeks
        $datediff = floor($difference / 604800);
        break;
    case "h": // Number of full hours
        $datediff = floor($difference / 3600);
        break;
    case "n": // Number of full minutes
        $datediff = floor($difference / 60);
        break;
    default: // Number of full seconds (default)
        $datediff = $difference;
        break;
    }    
    return $datediff;
}

    public function filterSpecialChars($s) {
        $result = htmlentities($s);
        $result = preg_replace('/^(&quot;)(.*)(&quot;)$/', "$2", $result);
        $result = preg_replace('/^(&laquo;)(.*)(&raquo;)$/', "$2", $result);
        $result = preg_replace('/^(&#8220;)(.*)(&#8221;)$/', "$2", $result);
        $result = preg_replace('/^(&#39;)(.*)(&#39;)$/', "$2", $result);
        $result = html_entity_decode($result);
        return $result;
    }
    
    public static function createRandomString($length = 7){
        $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

        return $randomString;
    }
    
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

        
?>
