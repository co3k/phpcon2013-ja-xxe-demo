--TEST--
Usage of libxml_disable_entity_loader()
--FILE--
<?php
$old = libxml_disable_entity_loader(true);
$xml = new SimpleXMLElement('<a></a>');
libxml_disable_entity_loader($old);
?>
--EXPECT--
