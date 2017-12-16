<?php namespace Dwedaz\IBank;
use Symfony\Component\Yaml\Yaml;
use Exception;

Class Client{
	public $bank;
	private $accounts;

	public function __construct($file = 'accounts.yml',$debug=false,$cache = false){
		$this->accounts = Yaml::parseFile($file);
		$this->debug = $debug;
		$this->cache = $cache;
	}

	public function getSaldo($accountName){
		if (!isset($this->accounts['accounts'][$accountName])){
			throw new Exception("$accountName is not set on account config file");
		}

		$data = $this->accounts['accounts'][$accountName];
		extract($data);
		$account = new Account($bank,$account,$username,$password);
		
		$className = sprintf('Dwedaz\IBank\%s\%s',$bank,$bank);

		$bank = new $className($account);
		$bank->debug = $this->debug;
		$bank->cache = $this->cache;
		return $bank->getSaldo();
		
	}

}