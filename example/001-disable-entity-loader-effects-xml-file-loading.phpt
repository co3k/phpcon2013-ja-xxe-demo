--TEST--
libxml_disable_entity_loader() effects XML file loading functions
--FILE--
<?php

$load = function () {
    $path = __DIR__.'/../resources/noext.atom';

    $dom = new DOMDocument();
    $reader = new XMLReader();

    echo 'DOMDocument::load() : '.var_export(@$dom->load($path), true).PHP_EOL;
    echo 'simplexml_load_file() : '.var_export((bool)@simplexml_load_file($path), true).PHP_EOL;
    echo 'XMLReader::open() : '.var_export(@$reader->open($path), true).PHP_EOL;
};

echo 'Entity loader enabled'.PHP_EOL;
libxml_disable_entity_loader(false);
$load();

echo PHP_EOL;

echo 'Entity loader disabled'.PHP_EOL;
libxml_disable_entity_loader(true);
$load();
?>
--EXPECT--
Entity loader enabled
DOMDocument::load() : true
simplexml_load_file() : true
XMLReader::open() : true

Entity loader disabled
DOMDocument::load() : false
simplexml_load_file() : false
XMLReader::open() : false
