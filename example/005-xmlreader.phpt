--TEST--
XMLReader
--FILE--
<?php

chdir(__DIR__.'/../resources');
$path = './ext.xml';
$content = file_get_contents($path);

$xml = new XMLReader();
$xml->xml($content);
$xml->setParserProperty(XMLReader::SUBST_ENTITIES, true);

$inTitle = false;
while (@$xml->read()) {
    if (XMLReader::ELEMENT === $xml->nodeType && 'title' === $xml->name) {
        echo trim($xml->readString()).PHP_EOL;
    }
}
?>
--EXPECT--
example REPLACED
