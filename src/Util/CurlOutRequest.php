<?php

namespace Leveon\Connector\Util;


class CurlOutRequest{
	
	#prop ch vGS
	private $ch;
	#prop _url vgS
	private $_url;
	#prop _method vgS
	private $_method = "GET";
	#prop _data vgS
	private $_data;
	#prop _query vgS
	private $_query;
	#prop _ssl vGS
	private $_ssl = false;
	#prop _host vgS
	private $_host;
	#prop _headers vGS
	private $_headers = [];
	#prop response vgS
	private $response;
	#prop responseCode vgS
	private $responseCode;
	#prop responseHeaders vgS
	private $responseHeaders;
	
	public function __construct($url = null){
		$this->_url = $url;
	}
	
	public static function New($url= null){
		return new self($url);
	}
	public static function Get($url, $query = null){
		return self::New($url)
			->query($query);
	}
	
	public static function Post($url, $data){
		return self::New($url)
			->method('POST')
			->data($data);
	}
	
	public function do(){
		$prot = $this->_ssl? "https://":"http://";
		$qs = $this->_query? '?'.http_build_query($this->_query): '';
		$url = $prot.$this->_host.$this->_url.$qs;
		$this->ch = curl_init($url);
		curl_setopt_array($this->ch, $this->def());
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->ch, CURLOPT_HEADER, 1);
		if($this->_ssl){
			curl_setopt_array($this->ch, [
				CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_VERBOSE        => 1
			]);
		}
		if($this->_method==="POST"){
			curl_setopt_array($this->ch, [
    	  CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $this->_data,
			]);
		}elseif($this->_method!=="GET"){
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, strtoupper($this->_method));
			curl_setopt_array($this->ch, [
    	  CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $this->_data,
			]);
		}
		if(count($this->_headers)>0){
			$h = [];
			foreach($this->_headers as $n => $v){
				$h[] = "{$n}: {$v}";
			}
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $h);
		}
		$response = curl_exec($this->ch);
		$header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$this->parseHeaders(explode("\n", $header));
		$this->response = substr($response, $header_size);
		if(intdiv($this->responseCode, 100)!==2)
			var_dump($response);
		return $this;
	}
	
	public function addHeader($name, $value){
		$this->_headers[$name] = $value;
		return $this;
	}
	
	public function removeHeader($name){
		if(isset($this->_headers[$name])) unset($this->_headers[$name]);
		return $this;
	}
	
	private function parseHeaders($headers){
		foreach($headers as $k=>$v){
			$t = explode(':', $v, 2);
			if(isset($t[1])) {
			  if(!isset($this->responseHeaders[trim($t[0])]))
			    $this->responseHeaders[trim($t[0])] = trim($t[1]);
			  else 
			    $this->responseHeaders[trim($t[0])] .= "\n".trim($t[1]);
			}
			else{
				if(preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out)){
					$this->responseCode = intval($out[1]);
					$this->responseHeaders = [];
				}
			}
		}
	}
	
	public function ssl($ssl = true){ $this->_ssl = $ssl; return $this; }
	public function url($url){ $this->_url = $url; return $this; }
	public function method($method){ $this->_method = $method; return $this; }
	public function data($data){ $this->_data = $data; return $this; }
	public function query($query){ $this->_query = $query; return $this; }
	public function host($host){ $this->_host = $host; return $this; }
	
	public function getResponseHeader($key){
		return isset($this->responseHeaders[$key])? $this->responseHeaders[$key]: null;
	}
	
	#gen - begin

	public function get_url(){ return $this->_url; }
	public function get_method(){ return $this->_method; }
	public function get_data(){ return $this->_data; }
	public function get_query(){ return $this->_query; }
	public function get_host(){ return $this->_host; }
	public function getResponse(){ return $this->response; }
	public function getResponseCode(){ return $this->responseCode; }
	public function getResponseHeaders(){ return $this->responseHeaders; }

	#gen - end
	
	private static function def(){
		return array(

      CURLOPT_RETURNTRANSFER => true,         // return web page

      CURLOPT_HEADER         => false,        // don't return headers

      CURLOPT_FOLLOWLOCATION => true,         // follow redirects

      CURLOPT_ENCODING       => "",           // handle all encodings

      CURLOPT_USERAGENT      => "spider",     // who am i

      CURLOPT_AUTOREFERER    => true,         // set referer on redirect

      CURLOPT_CONNECTTIMEOUT => 120,          // timeout on connect

      CURLOPT_TIMEOUT        => 120,          // timeout on response

      CURLOPT_MAXREDIRS      => 10,           // stop after 10 redirects


        
    );		
	}
	
	
}