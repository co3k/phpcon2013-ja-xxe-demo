--TEST--
SimpleXML
--FILE--
<?php

chdir(__DIR__.'/../resources');
$path = './ext.xml';
$content = file_get_contents($path);

$simpleXml = @new SimpleXMLElement($content);
$simpleXmlUrl = @new SimpleXMLElement($path, null, true);
$simpleXmlLoadString = @simplexml_load_string($content);
$simpleXmlLoadFile = @simplexml_load_file($path);

echo 'SimpleXMLElement::__constructor($content) : '.trim((string)$simpleXml->entry->title).PHP_EOL;
echo 'SimpleXMLElement::__constructor($path) : '.trim((string)$simpleXmlUrl->entry->title).PHP_EOL;
echo 'simplexml_load_string() : '.trim((string)$simpleXmlLoadString->entry->title).PHP_EOL;
echo 'simplexml_load_file() : '.trim((string)$simpleXmlLoadFile->entry->title).PHP_EOL;
?>
--EXPECT--
SimpleXMLElement::__constructor($content) : example REPLACED
SimpleXMLElement::__constructor($path) : example REPLACED
simplexml_load_string() : example REPLACED
simplexml_load_file() : example REPLACED
