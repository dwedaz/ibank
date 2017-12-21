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

	public function parseStatement($html){
		$crawler = new Crawler($html);
		try{
			$rows = $crawler->filterXpath('//*[@id="pagebody"]/span/table[2]/tr[2]/td[2]/table/tr');
			$i=1;
			foreach($rows as $row){
				if($i===1) {$i++; continue;}
				$row = $row->ownerDocument->saveHTML($row);
				$row = new Crawler($row);
				$cont = $row->filterXpath("//td[2]")->html();
				$cont = explode('<br>',$cont);
			

				$data[] = [
		            'date' => $row->filterXpath("//td[1]")->text(),
		            'desc' => $cont[2] . $cont[3],
		            'branch' => $cont[4],
		            'amout' => ($cont[5]),
		            'type' => $cont[0],
		            
		       	 ];
			}
		}catch(Exception $e){

		}

		print_r($data);
		exit;
	}




}