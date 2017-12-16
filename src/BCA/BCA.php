<?php namespace Dwedaz\IBank\BCA;

use Dwedaz\IBank\BankBase;
use GuzzleHttp\Client;
use Dwedaz\IBank\Account;
use Symfony\Component\DomCrawler\Crawler;
use Exception;
final class BCA extends BankBase{
	
	public function __construct(Account $account){

		parent::__construct($account);
		$this->browser = new Client(
			[	'base_url' => 'ibank.klikbca.com',
				'headers' => [
			       'Upgrade-Insecure-Requests' => '1',
					'User-Agent' => 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.76 Mobile Safari/537.36',
					'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
					'Accept-Encoding' => 'gzip, deflate, sdch, br',
					'Accept-Language' => 'en-US,en;q=0.8,id;q=0.6,fr;q=0.4'
			    ],
			    'cookies'=>true,
	   		]
	    );

	    
	}

	public function parseBalanceInquiry($html){
	
		$crawler = new Crawler($html);
		try{
			$balance = $crawler->filterXpath('//td[@align="right"]')->text();

		}catch(Exception $e){

		}
		$balance = (int) str_replace(',','',$balance);
		
		$this->account->setBalance($balance);
	
	}

	public function isLogin($html){
	
		$crawler = new Crawler($html);
		try{
			$userid = $crawler->filterXpath('//input[@id="user_id"]');

		}catch(Exception $e){

		}
		
		if($userid->count()){
			throw new Exception('User login failed');
		}
	
	}




}