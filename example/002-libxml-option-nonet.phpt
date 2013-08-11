--TEST--
libxml parser option, XML_PARSE_NONET (LIBXML_NONET), disables replacement of external entity
--FILE--
<?php

$load = function ($str, $option = 0) {
    $dom = new DOMDocument();
    @$dom->loadXML($str, $option);

    $xml = @simplexml_load_string($str, 'SimpleXMLElement', $option);

    echo 'DOM:'.trim(@$dom->getElementsByTagName('elm')->item(0)->textContent).PHP_EOL;
    echo 'SimpleXML:'.trim(@$xml->elm).PHP_EOL;
};

$str = '<!DOCTYPE root [ <!ENTITY a SYSTEM "http://localhost.dummy.co3k.org/xxe-resources/include.xml"> ]> <root><elm>&a;</elm></root>';

echo 'No options'.PHP_EOL;
$load($str);

echo PHP_EOL;

echo 'With LIBXML_NOENT'.PHP_EOL;
$load($str, LIBXML_NONET);

?>
--EXPECT--
No options
DOM:REPLACED
SimpleXML:REPLACED

With LIBXML_NOENT
DOM:
SimpleXML:
