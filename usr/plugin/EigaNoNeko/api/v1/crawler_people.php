<?php
$page = ($_GET['page'] ?? 1);

header('content-type: text/plain');
$url = "https://api.jikan.moe/v4/people?page=".$page;
$json = __OmsyJSON->decode(request($url));

for ($i=intval($page); $i < $json->pagination->last_visible_page; $i++) {
	$url = "https://api.jikan.moe/v4/people?page=".$i;
	$json = __OmsyJSON->decode(request($url));

	foreach ($json->data as $value) {
		$uniqid = uniqid();
		$newUrl = downloadImage($value->images->jpg->image_url);
		__OmsySql->execute(
			"INSERT INTO encore_peoples(uniqid, image, name, given_ame, family_name, alternate_name, birthday, about) VALUES (?,?,?,?,?,?,?,?)",
			[$uniqid, $newUrl, ($value->alternate_names[0] ?? $value->name), $value->given_name, $value->family_name, __OmsyJSON->encode($value->alternate_names), $value->birthday, $value->about]
		);
	}

	sleep(1);
	set_time_limit(60);
}

function request($string) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt(
		$ch,
		CURLOPT_USERAGENT,
		"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
	);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Pour les tests
	$html = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if ($httpCode != 200) {
		$ignoredFile[] = $string;
		return false;
	}

	return $html;
}

function downloadImage($url) {
	$file = "share/content-delivry/voiceactors/voiceactors-".uniqid().'.jpg';
	$context = stream_context_create([
		"http" => [
			"timeout" => 10,
			"header"  => "User-Agent: Mozilla/5.0\r\n"
		]
	]);

	file_put_contents(
		__Root . "$file",
		file_get_contents($url, false, $context)
	);
	return "$file";
}