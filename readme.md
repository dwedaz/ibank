PHP Internet Banking Crawler
==================================
Indonesia Bank Account Crawler
Support Bank:
	* BCA


### Installation

requires [PHP > 5.4) to run.

Install the dependencies and devDependencies and run the script.

```sh
$ git clone https://github.com/dwedaz/ibank.git
$ composer update -vvv
$ cp accounts.example.yml accounts.yml
$ php example.php
```

### Configure
put your configure like this

```yaml
  bca_jakarta:
    bank: BCA
    account: 527XXXXXX
    username: DWEDAZUD0101
    password: 999999
  bca_pontianak:
    bank: BCA
    account: 029XXXXXX
    username: DWEDAZUD0020
    password: 999999
```
### Script
how to use
```php
require 'vendor/autoload.php';
$mybank = new Dwedaz\IBank\Client();
$account = $mybank->getSaldo('bca_jakarta');
print_r($account);
$account = $mybank->getSaldo('bca_pontianak');
	print_r($account);
```


easy to handle and configure.

Basic useful feature list:

 * get last balance/saldo
 * multi account 
 * debug mode
 * cache mode

### Todos

 - Add Another Bank
 - Add Creditcard
 - Add Mutation
