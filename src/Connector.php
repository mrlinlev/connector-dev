<?php

namespace Leveon\Connector;

use Leveon\Connector\Util\CurlOutRequest;

class Connector{
	
	#prop params vGS aprot
	protected $params;
	
	public function __construct(){
		$this->params = require(__DIR__.'/config.php');
	}
	
	public function request($url, $method = 'GET'){
		return CurlOutRequest::New($url)
			->method($method)
			->host($this->params['host'])
			->ssl($this->params['ssl'])
			->addHeader('X-API-KEY', $this->params['key'])
			->addHeader('content-type', 'application/json')
			;
	}
	
	public function get($url){
		return $this->request($url);
	}
	
	public function post($url, $data = null){
		$req = $this->request($url, "POST");
		return $data!==null? $req->data(json_encode($data)): $req;
	}
	
	public function patch($url, $data = null){
		$req = $this->request($url, "PATCH");
		return $data!==null? $req->data(json_encode($data)): $req;
	}
	
	public function put($url, $data = null){
		$req = $this->request($url, "PUT");
		return $data!==null? $req->data(json_encode($data)): $req;
	}
	
	public function delete($url, $data = null){
		$req = $this->request($url, "DELETE");
		return $data!==null? $req->data(json_encode($data)): $req;
	}


    /**
     * Подписание и отправка запроса к апи
     * @param CurlOutRequest $req
     * @return CurlOutRequest
     */
    public function process(CurlOutRequest $req){
		$fullUrl = $req->get_url();
		if($req->get_query()){
			$fullUrl .= '?'.http_build_query($req->get_query());
		}
		$time = strtotime('now')*1000;
		$imp = [
			$this->params['signKey'],
			$fullUrl,
			$time
		]; 
		if($req->get_data()!==null)
			$imp[] = $req->get_data();
		return $req
			->addHeader('X-DATE', $time)
			->addHeader('X-SIGN', hash('sha256', implode('-', $imp)))
			->do();
	}
	
	
	#gen - begin


	#gen - end
}