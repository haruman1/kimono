<?php
set_time_limit(0);
$token = "EAAAAKLSe4lIBANjBPgQ0UJ6ndDU0XTtZAjjRG7QrpR5uqUr3bZCfI9ZBNppwUxr2AE2F7ZAESjByeeNWIgHdktag4nPYXcoxQEON1lGaOs7n5Q6cZA65P00XtZBjHR5XC5yVXmFV06xhgQBne53hVuYi0WP7OzZAuPFuripaDPWBSzGAPGBiuPi";
require_once('color.php');
function friendlist($token){
	$a = json_decode(file_get_contents('https://graph.facebook.com/me/friends?access_token='.$token), true);
	return $a['data'];
}
function last_active($id, $tok){
	$a = json_decode(file_get_contents('https://graph.facebook.com/'.$id.'/feed?access_token='.$tok.'&limit=1'), true);
	$date = $a['data'][0]['created_time'];
	$aa = strtotime($date);
	return date('Y', $aa);
}
function unfriend($id, $token){
	$url = 'https://graph.facebook.com/me/friends?uid='.$id.'&access_token='.$token;
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
	if($result == true){
		$unf = Console::green('[UNFRIENDED]');
	} else {
		$unf = Console::red('[FAILED TO UNFRIEND]');
	}
	return $unf;
}
echo Console::blue("     Facebook Auto Unfriend\n");
echo Console::blue("        Inactive Users\n\n");
//INPUT
echo "Facebook token "
$fbtoken = trim(fgets(STDIN));
echo "Year : ";
$year = trim(fgets(STDIN));
echo "\n";
$FL = friendlist($fbtoken);
foreach($FL as $list){
	$name = $list['name'];
	$id = $list['id'];
	$date = last_active($id, $fbtoken);
	if($date < $year){
		echo Console::red('[INACTIVE]').' '.$name.' ~ '.$date.' '.unfriend($id, $fbtoken);
		echo "\r\n";
	}else{
		echo Console::green('[ACTIVE]').' '.$name.' ~ '.$date;
		echo "\r\n";
	}
