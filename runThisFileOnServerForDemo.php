<?php
	include "./SimpleTranslate.class.php";
	$t = new SimpleTranslate();

	// set the target language (optional, will auto detect browser default language if you don't call this)
	if($_GET["targetLanguage"]) $t->setTargetLanguage($_GET["targetLanguage"]);
?>
<html>
<head>
	<title>PHPSimpleTranslation demo</title>
</head>
<body>
	<div>You are viewing this page in language: <?=$t->targetLanguage?></div>
	<div><?=$t->localizedString("This is some English text to be translated");?></div>
	<div><button onclick="window.location='runThisFileOnServerForDemo.php?targetLanguage=en'">English</button></div>
	<div><button onclick="window.location='runThisFileOnServerForDemo.php?targetLanguage=ko'">Korean</button></div>
	<div><button onclick="window.location='runThisFileOnServerForDemo.php?targetLanguage=zh-hans'">Simplified Chinese</button></div>
	<div><button onclick="window.location='runThisFileOnServerForDemo.php?targetLanguage=zh-hant'">Traditional Chinese</button></div>
</body>	
</html>