## PHPSimpleTranslation

PHPSimpleTranslation is a dead easy, super simple drop in translation class for localizing strings on your PHP based website. Just create the translation files in the right format and use the translate function to localize strings based on the default browser language or other language of your choice.


#### Usage

Just include and load the class, set your target language, and use the 'localizedString()' function to translate your strings. If a translation isn't available, 'localizedString()' will just return the default string that was inserted.

```
	// Include and load the class
	include "./PHPSimpleTranslation/SimpleTranslate.class.php";
	$t = new SimpleTranslate();
	// set the target language (optional, will auto detect browser default language if you don't call this)
	$t->setTargetLanguage($targetLanguageCode);
	
	// Use the localizedString function to get translated text
	echo $t->localizedString("This is some text to be translated");
```

#### Adding Languages

You can add languages by adding lang files to the 'languages' dir. Language file names should be the language code with a 'lang' extension. For example; if you wanted to add a Korean language file create the file ko.lang where 'ko' is the language code for the Korean language. You can also add languages like 'pt-br.lang' along with 'pt.lang' if you want to specifically support Brazilian Portuguese along side generic Portuguese.

File format should look like this:

```
	"Default text" = "Translated text";
```

You can add as many languages as you want.

#### TODO

The language detection part is a bit hacky. Anyone that was a better idea on how to handle this please feel free to contribute.