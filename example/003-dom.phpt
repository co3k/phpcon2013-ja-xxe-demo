--TEST--
DOM
--FILE--
<?php

chdir(__DIR__.'/../resources');
$path = './dom.xhtml';
$content = file_get_contents($path);

$document = new DOMDocument();
$htmlDocument = clone $document;
$htmlFileDocument = clone $document;
$xmlDocument = clone $document;

// $path
@$document->load($path);
@$htmlFileDocument->loadHTMLFile($path);

// $content
@$htmlDocument->loadHTML($content);
@$xmlDocument->loadXML($content);

echo '::load($path) : '.trim(@$document->getElementsByTagName('title')->item(0)->textContent).PHP_EOL;
echo '::loadXML($content) : '.trim(@$xmlDocument->getElementsByTagName('title')->item(0)->textContent).PHP_EOL;
echo '::loadHTMLFile($path) : '.trim(@$htmlFileDocument->getElementsByTagName('title')->item(0)->textContent).PHP_EOL;
echo '::loadHTML($content) : '.trim(@$htmlDocument->getElementsByTagName('title')->item(0)->textContent).PHP_EOL;
?>
--EXPECT--
::load($path) : example REPLACED
::loadXML($content) : example REPLACED
::loadHTMLFile($path) : example &include;
::loadHTML($content) : example &include;
