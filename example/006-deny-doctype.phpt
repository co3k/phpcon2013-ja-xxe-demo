--TEST--
Deny DOCTYPE
--FILE--
<?php

$validate = function ($input) {
    $reader = new XMLReader();
    $reader->XML($input);

    $result = false;
    while (@$reader->read()) {
        $result = true;

        if (XMLReader::DOC_TYPE === $reader->nodeType) {
            $result = false;
            break;
        }
    }

    return $result;
};

$prolog = '<?xml version="1.0" encoding="UTF-8"?><!-- comment -->';
$valid = '<root><elm>valid</elm></root>';
$invalid = '<!DOCTYPE root [ <!ENTITY a1 "bomb"><!ENTITY a2 "&a1;&a1;"><!ENTITY a3 "&a2;&a2;"><!ENTITY a4 "&a3;&a3;"><!ENTITY a "&a4;&a4;"> ]> <root><elm>valid&a;</elm></root>';

var_dump($validate($valid));
var_dump($validate($prolog.$valid));
var_dump($validate($invalid));
var_dump($validate($prolog.$invalid));
?>
--EXPECT--
bool(true)
bool(true)
bool(false)
bool(false)
