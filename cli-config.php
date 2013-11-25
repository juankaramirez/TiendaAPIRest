<?php
require_once 'vendor/autoload.php';
require_once "bootstrap.php";
//return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em); //Esto es para doctrine 2.4
return $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));