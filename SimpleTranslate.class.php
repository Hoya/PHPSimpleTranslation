<?php
define("languageFileDir", realpath(dirname(__FILE__))."/languages");

class SimpleTranslate
{
	public $targetLanguage;
	private $translationData;

	// let's get started
	function __construct($targetLanguage = NULL)
	{
		// define the target language if provided
		$this->setTargetLanguage($targetLanguage);
	}

	// define the target language
	public function setTargetLanguage($targetLanguage)
	{
		$targetLanguage = strtolower($targetLanguage);
		if(!$targetLanguage)
		{
			$targetLanguage = $this->detectSupportedLanguage();
		}
		else
		{
			$targetLanguage = $this->mapToSupportedLanguage($targetLanguage);
		}

		$this->targetLanguage = $targetLanguage;
		$this->translationData = array();
		$this->loadLanguageData();
	}
	
	// auto detect default browser language
	public function detectSupportedLanguage()
	{
		$http_accept_language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
		preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);
		$detectedSupportedLanguage = NULL;
		foreach ($matches as $match)
		{
		    list($languageCode, $languageRegion) = explode("-", $match[1]) + array("", "");
		    $priorityLevel = isset($match[2]) ? (float) $match[2] : 1.0;
		    $fullLanguageCode = $languageCode."-".$languageRegion;

			$detectedSupportedLanguage = $this->mapToSupportedLanguage($fullLanguageCode);
			if($detectedSupportedLanguage) break;
		}

		// default to english
		if(!$detectedSupportedLanguage) $detectedSupportedLanguage = "en";
		return $detectedSupportedLanguage;
	}

	// map default language to best supported langauge
	private function mapToSupportedLanguage($targetLanguage)
	{
		list($languageCode, $languageRegion) = explode("-", $targetLanguage) + array("", "");

		// hack for Chinese langauges, should find better way to do this
		$additionalMapping = array(
				"zh" => "zh-hans",
				"zh-cn" => "zh-hans",
				"zh-sg" => "zh-hans",
				"zh-tw" => "zh-hant",
				"zh-tw" => "zh-hant"
			);
		if(array_key_exists($targetLanguage, $additionalMapping))
		{
			$targetLanguage = $additionalMapping[$targetLanguage];
		}

		// check if supported language file exists
		$languageFilePath = languageFileDir."/".$targetLanguage.".lang";
		$altLanguageFilePath = languageFileDir."/".$languageCode.".lang";
			
		if(file_exists($languageFilePath))
		{
			return $targetLanguage;
		}
		else if(file_exists($altLanguageFilePath))
		{
			return $languageCode;
		}
		else
		{
			// no supported languages
			return NULL;
		}
	}

	// load the language data from file
	protected function loadLanguageData()
	{
		$currentLanguage = array();
		$languageFilePath = languageFileDir."/".$this->targetLanguage.".lang";
		if(!file_exists($languageFilePath)) return;

		$fileInfo = pathinfo($languageFilePath);
		$language = $fileInfo["filename"];

		$handle = fopen($languageFilePath, "r");
		if($handle)
		{
			$lineNumber = 0;
			while (($line = fgets($handle)) !== false)
			{
				// process each line
				if(strlen($line) > 0)
				{
					$result = preg_match("/(^\\s*[\"](.*)[\"]\\s*=\\s*[\"](.*)[\"][;]\\s*$)||\\s*/", $line, $output);
					if($result == FALSE) throw new Exception("Error reading language file ".$languageFile);
					$lineNumber++;
					if(count($output) == 1) continue;

					$key = $output[2];
					$value = $output[3];
					$currentLanguage[$key] = $value;
				}
				else
				{
					$lineNumber++;
					continue;
				}
			}
			fclose($handle);
		}
		else
		{
			throw new Exception("Error opening language file ".$languageFile);
		}

		$this->translationData = $currentLanguage;
		
	}

	// translate the string
	public function localizedString($string)
	{
		if(!$this->targetLanguage) throw new Exception("A target language has not been defined");

		if(array_key_exists($string, $this->translationData))
		{
			return $this->translationData[$string];
		}
		else
		{
			return $string;
		}
	}
}
?>