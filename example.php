<?php

require 'vendor/autoload.php';


$mybank = new Dwedaz\IBank\Client('accounts.yml',true,false);


$account = $mybank->getBalance('bca_jakarta');

echo ($account);

$account = $mybank->getBalance('bca_pontianak');

echo ($account);
