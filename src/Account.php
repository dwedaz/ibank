<?php namespace Dwedaz\IBank;

Class Account{

	public function __construct($bank,$account,$username,$password){
		$this->bank     = $bank;
		$this->account  =  $account;
		$this->username =  $username;
		$this->password =  $password;
	}

	public function setBalance($balance){
		$this->balance = $balance;
	}

	public function __toString(){
		return json_encode($this);
	}
}