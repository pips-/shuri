<?php
if(!empty($_GET['url'])) {
	$url		= $_GET['url'];

	if(strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
		$url = 'http://'.$url;
	}

	$urlhash	= sha1($url);

	$hashfolder	= substr($urlhash, 0, 2);
	$hashfile	= substr($urlhash, 2);

	$hashfolderpath	= './db/' . $hashfolder;
	$hashfilepath	= $hashfolderpath . '/' . $hashfile;

	mkdir($hashfolderpath, 0700, true);

	file_put_contents($hashfilepath, $url);
	$content = '<a href="./?'.$urlhash.'">./?'.$urlhash.'</a><br />
			As long as there is not collision, you can shorten it down to 3 characters.';
} else if(!empty($_GET)) {
	$urlhash = key($_GET);

	$hashfolder	= substr($urlhash, 0, 2);
	$hashfile	= substr($urlhash, 2);

	$hashfolderpath	= './db/' . $hashfolder;
	$hashfilepath	= $hashfolderpath . '/' . $hashfile;

	$findfiles	= glob($hashfilepath . '*');

	if(empty($findfiles)) {
		$content .= 'No files.';
		return 1;
	} else if (count($findfiles) > 1) {
		foreach($findfiles as $file) {
			$file = str_replace('/', '', substr($file, 5));
			$content .= '<a href="./?'.$file.'">./?'.$file.'</a><br />';
		}
	}

	$fullfilepath = current($findfiles);

	header('Location:' . file_get_contents($fullfilepath));
	return 0;
} else {
	$content = '<form method="get">
				Enter your URL: <input type="text" name="url"><input type="submit" value="Submit">
			</form>';
}

//actual page below
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Shuri</title>
	</head>
	<body>
		<div id="content">
			<?php echo $content ?>

		</div>
	</body>
</html>
