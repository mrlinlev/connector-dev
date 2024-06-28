<?php

namespace Leveon\Connector;

use Leveon\Connector\Exceptions\ConfigurationException;
use Leveon\Connector\Util\CurlOutRequest;
use Leveon\Connector\Util\CurlOutResponse;

class Connector{

    /**
     * @param string $url
     * @param string $method
     * @return CurlOutRequest
     * @throws ConfigurationException
     */
    public function request(string $url, string $method = 'GET'): CurlOutRequest
    {
		return CurlOutRequest::New($url)
			->method($method)
			->host(Leveon::getConfig('host', 'api.cds.leveon.ru'))
			->ssl(Leveon::getConfig('ssl', true))
			->addHeader('X-API-KEY', Leveon::requireConfig('key'))
			->addHeader('content-type', 'application/json');
	}

    /**
     * @param string $url
     * @return CurlOutRequest
     * @throws ConfigurationException
     */
    public function get(string $url): CurlOutRequest
    {
		return $this->request($url);
	}

    /**
     * @param string $url
     * @param $data
     * @return CurlOutRequest
     * @throws ConfigurationException
     */
    public function post(string $url, $data = null): CurlOutRequest{
		$req = $this->request($url, "POST");
		return $data!==null? $req->data(json_encode($data)): $req;
	}

    /**
     * @param string $url
     * @param $data
     * @return CurlOutRequest
     * @throws ConfigurationException
     */
    public function patch(string $url, $data = null): CurlOutRequest{
		$req = $this->request($url, "PATCH");
		return $data!==null? $req->data(json_encode($data)): $req;
	}

    /**
     * @param string $url
     * @param $data
     * @return CurlOutRequest
     * @throws ConfigurationException
     */
    public function put(string $url, $data = null): CurlOutRequest{
		$req = $this->request($url, "PUT");
		return $data!==null? $req->data(json_encode($data)): $req;
	}

    /**
     * @param string $url
     * @param $data
     * @return CurlOutRequest
     * @throws ConfigurationException
     */
    public function delete(string $url, $data = null): CurlOutRequest{
		$req = $this->request($url, "DELETE");
		return $data!==null? $req->data(json_encode($data)): $req;
	}

    /**
     * Подписание и отправка запроса к апи
     * @param CurlOutRequest $req
     * @return CurlOutRequest
     * @throws ConfigurationException
     */
    public function process(CurlOutRequest $req): CurlOutResponse
    {
		$fullUrl = $req->get_url();
		if($req->get_query()){
			$fullUrl .= '?'.http_build_query($req->get_query());
		}
		$time = strtotime('now')*1000;
		$imp = [
            Leveon::requireConfig('signKey'),
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
}