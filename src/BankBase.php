<?php namespace Dwedaz\IBank;
use Symfony\Component\Yaml\Yaml;
use GuzzleHttp\Client;
use Exception;
use Carbon\Carbon;

abstract class BankBase{
	private $username;
	private $password;
	public $bankName;


	public function __construct(Account $account){
		$this->account = $account;
		$this->browser = new Client();
		$this->debug = false;
		$this->cache = false;
		foreach(['menu','step'] as $varname){
			$filename = sprintf('%s/%s/%s-%s.yml',dirname(__FILE__),$this->account->bank,$this->account->bank,ucfirst($varname));

			$this->$varname =  Yaml::parseFile($filename);
		}
		
	}

	private function visit($step){
		if(!isset($this->menu[$step])) throw new Exception("Menu $step is not declare");
		$var = $this->menu[$step];

		$params = [];
		extract($var);

		$xmethod = strtoupper($method);
		
		if(!$this->cache){
			if($xmethod == 'GET'){
				$req = $this->browser->$method($url,[]);
			}else if ($xmethod == 'POST'){
				$this->mapParams($params);
				$req = $this->browser->$method($url,[
					'form_params' => $params]);
			}
		}


		if($this->debug && !$this->cache){
			if (!file_exists(dirname(dirname(__FILE__)).'/debug/'))
				@mkdir(dirname(dirname(__FILE__)).'/debug/');
	
			$html =  $req->getBody()->getContents();
			//$html->seek(0);
			file_put_contents(dirname(dirname(__FILE__)).'/debug/'.$this->account->account.$step.'.params',json_encode($params));
			file_put_contents(dirname(dirname(__FILE__)).'/debug/'.$this->account->account.$step.'.html',$html);
		}else if($this->cache){
			$html = file_get_contents(dirname(dirname(__FILE__)).'/debug/'.$this->account->account.$step.'.html');
		}

		if (isset($parse)){
			$this->$parse($html);
		}
		
		return $html;
		
	}

	private function mapParams(&$params){
		foreach($params as $key=>$value){
			if($value[0]==='!'){
				$xvalue = substr($value,1);
				if(isset($this->account->$xvalue)) $params[$key]=$this->account->$xvalue;

			}elseif($value[0]==='%'){
				$xvalue = substr($value,1);
				$end = Carbon::now();
       			$start = Carbon::now()->subDays(7);
				switch($xvalue){
					case 'start-d':
						$value = $start->format('d');
						break;
					case 'start-m':
						$value = $start->format('m');
						break;
					case 'start-Y':
						$value = $start->format('Y');
						break;
					case 'end-d':
						$value = $end->format('d');
						break;
					case 'end-m':
						$value = $end->format('m');
						break;
					case 'end-Y':
						$value = $end->format('Y');
						break;
					default:
						break;

				}
				
				$params[$key]=$value;


			}
		}
	}

	public function __call($name, $arguments)
    {

    	if(isset($this->step[$name])){

			foreach($this->step[$name] as $step){
				
				try{			
					$this->visit($step);
				}catch(\Exception $e){
					$this->account->error = $e->getMessage();
				}
			}
			
			return $this->account;
    	}else{
    		throw new Exception("$name is not defiend");
    	}
    }


}