<?php

require 'vendor/autoload.php';


$mybank = new Dwedaz\IBank\Client('accounts.yml',true,true);


$account = $mybank->getSaldo('bca_jakarta');

echo ($account);

$account = $mybank->getSaldo('bca_pontianak');

echo ($account);






