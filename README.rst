==================================================
XXE and XMLBomb demo for PHP Conference Japan 2013
==================================================

What's?
=======

Structure
=========

Vulnerable Checker
==================

This demo has a simple vulnerable checker of XXE and XML Bomb:

    $ cd /path/to/this/repository
    $ /path/to/your/php check.php

Then you will get some outputs like the following:

    -----------------------
    PHP Version: 5.5.1
    libxml2 Version: 2.8.0
    -----------------------
    XML Bomb: might be [SAFE]
    XXE: might be [VULNERABLE]

If [SAFE], your PHP and libxml2 might be safe from this vulnerability.

If [VULNERABLE], your PHP and libxml2 might be vulnerable so you need to add your own safeguard.

Demo : Simple Feed Reader
=========================

