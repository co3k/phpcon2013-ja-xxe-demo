<?php

$load = function ($str, $option = 0) {
    $xml = @simplexml_load_string($str, 'SimpleXMLElement', $option);

    return trim((string)@$xml->elm);
};

echo '-----------------------'.PHP_EOL;
echo 'PHP Version: '.phpversion().PHP_EOL;
echo 'libxml2 Version: '.LIBXML_DOTTED_VERSION.PHP_EOL;
echo '-----------------------'.PHP_EOL;
$str = '<!DOCTYPE root [ <!ENTITY a1 "bomb"><!ENTITY a2 "&a1;&a1;"><!ENTITY a3 "&a2;&a2;"><!ENTITY a4 "&a3;&a3;"><!ENTITY a "&a4;&a4;"> ]> <root><elm>&a;</elm></root>';
echo 'XML Bomb: might be ['.(strlen($load($str)) ? 'VULNERABLE' : 'SAFE').']'.PHP_EOL;
$str = '<!DOCTYPE root [ <!ENTITY a SYSTEM "/etc/passwd"> ]> <root><elm>&a;</elm></root>';
echo 'XXE: might be ['.(strlen($load($str)) ? 'VULNERABLE' : 'SAFE').']'.PHP_EOL;
echo PHP_EOL;
