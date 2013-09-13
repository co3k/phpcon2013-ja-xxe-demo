<?php

libxml_use_internal_errors(true);

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request, Symfony\Component\HttpFoundation\Response,
    Symfony\Component\Validator\Validation, Symfony\Component\Validator\Constraints, Guzzle\Http\Client,
    Aws\S3\S3Client
;

$logo = '';

if (is_file(__DIR__.'/../config.php')) {
    require_once __DIR__.'/../config.php';

    $s3 = S3Client::factory($config);
    $s3->registerStreamWrapper();

    try {
        $logo = base64_encode(file_get_contents('s3://phpcon/ebihara-150x150.jpg'));
    } catch (Exception $e) {
        // do nothing
    }
}

$request = Request::createFromGlobals();
$loader = new Twig_Loader_Filesystem(__DIR__.'/../template');
$twig = new Twig_Environment($loader);

// --

/**
 * Makes sure the specified string is a valid HTTP protocol URL
 */
$validate = function ($url) {
    $validator = Validation::createValidator();

    // $url must ...
    $constraints = [
        new Constraints\NotBlank(),  // not be blank input
        new Constraints\Url(),       // be a valid format of URL and its protocol is HTTP or HTTPS
    ];
    $violations = $validator->validateValue($url, $constraints);

    if (0 < $violations->count()) {
        throw new Exception($violations[0]->getMessage());
    }
};

/**
 * Retrieves feed contents from the specified URL
 */
$fetch = function ($url) {
    $client = new Client($url, [
        'redirect.disable' => true,
        'request.options' => [
            'timeout'         => 10,
            'connect_timeout' => 10,
        ],
    ]);

    return $client->get()
        ->send()
        ->getBody()
    ;
};

/**
 * Handles request and generates response body
 */
$controller = function ($request, $twig, $logo) use ($validate, $fetch) {
    if ('POST' !== $request->getMethod()) {
        // Display input interface
        return $twig->render('input.twig', [
            'logo' => $logo,
        ]);
    }

    // Retrive url parameter and validate it
    $url = $request->request->get('url');
    $validate($url);

    // Fetch feed content from URL
    $content = $fetch($url);

    // Restrict feed length to safe parse
    $limit = pow(2, 20);
    if ($limit < strlen($content)) {
        throw new Exception(sprintf('A content length of this feed is over than %d bytes', $limit));
    }

    // Parse feed
    $xml = new SimpleXmlIterator($content);
    if (0 < count($xml->channel)) {  // If this feed is RSS, treat the first <channel> element as root（ゝω・）てへぺろ
        $xml = $xml->channel[0];
    }
    $it = new AppendIterator();
    $it->append($xml->item); // RSS (・ω<) てへぺろ
    $it->append($xml->entry); // ATOM (・ω≦) てへぺろ

    $summaries = array();
    foreach ($it as $entry) {
        $summaries[] = (string)$entry->title;
    }

    // Display success content
    return $twig->render('success.twig', [
        'blog_title' => (string)$xml->title,
        'summaries'  => $summaries,
        'content'    => $content,
    ]);
};

// --

$response = new Response();
$response->prepare($request);

try {
    $response->setContent($controller($request, $twig, $logo));
} catch (Exception $e) {
    $libxmlErrors = libxml_get_errors();
    $libxmlMessages = '';

    if (0 < count($libxmlErrors)) {
        foreach ($libxmlErrors as $error) {
            $libxmlMessages .= $error->message;
        }

        $e = new Exception($e->getMessage().PHP_EOL.'and libxml reports the following errors:'.PHP_EOL.$libxmlMessages);
    }

    $response->setStatusCode(500);
    $response->setContent($twig->render('error.twig', [
        'exception' => $e,
    ]), 500);
}

$response->sendHeaders();
$response->sendContent();
