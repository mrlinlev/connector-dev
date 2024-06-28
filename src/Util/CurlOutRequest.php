<?php

namespace Leveon\Connector\Util;


class CurlOutRequest
{
    private $ch;
    private string $_url;
    private string $_method = "GET";
    private $_data;
    private $_query;
    private bool $_ssl = false;
    private string $_host;
    private array $_headers = [];
    private $response;
    private $responseCode;
    private $responseHeaders;

    public function __construct($url = null)
    {
        $this->_url = $url;
    }

    public static function New($url = null): static
    {
        return new self($url);
    }

    public static function Get($url, $query = null): static
    {
        return self::New($url)
            ->query($query);
    }

    public static function Post($url, $data): static
    {
        return self::New($url)
            ->method('POST')
            ->data($data);
    }

    public function resetConnection(): static
    {
        $this->ch = null;
        return $this;
    }

    public function do(): static
    {
        if($this->ch===null) $this->ch = curl_init();
        else curl_reset($this->ch);
        $protocol = $this->_ssl ? "https://" : "http://";
        $qs = $this->_query ? '?' . http_build_query($this->_query) : '';
        $url = $protocol . $this->_host . $this->_url . $qs;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt_array($this->ch, $this->defaults());
        if ($this->_ssl) {
            curl_setopt_array($this->ch, [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_VERBOSE => 1
            ]);
        }
        if ($this->_method === "POST") {
            curl_setopt_array($this->ch, [
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $this->_data,
            ]);
        } elseif ($this->_method !== "GET") {
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, strtoupper($this->_method));
            curl_setopt_array($this->ch, [
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $this->_data,
            ]);
        }
        if (count($this->_headers) > 0) {
            $h = [];
            foreach ($this->_headers as $n => $v) {
                $h[] = "{$n}: {$v}";
            }
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $h);
        }
        $response = curl_exec($this->ch);
        $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $this->parseHeaders(explode("\n", $header));
        $this->response = substr($response, $header_size);
        if (intdiv($this->responseCode, 100) !== 2)
            var_dump($response);
        return $this;
    }

    public function addHeader($name, $value): static
    {
        $this->_headers[$name] = $value;
        return $this;
    }

    public function removeHeader($name): static
    {
        if (isset($this->_headers[$name])) unset($this->_headers[$name]);
        return $this;
    }

    private function parseHeaders($headers): void
    {
        foreach ($headers as $k => $v) {
            $t = explode(':', $v, 2);
            if (isset($t[1])) {
                if (!isset($this->responseHeaders[trim($t[0])]))
                    $this->responseHeaders[trim($t[0])] = trim($t[1]);
                else
                    $this->responseHeaders[trim($t[0])] .= "\n" . trim($t[1]);
            } else {
                if (preg_match("#HTTP/[0-9.]+\s+([0-9]+)#", $v, $out)) {
                    $this->responseCode = intval($out[1]);
                    $this->responseHeaders = [];
                }
            }
        }
    }

    public function ssl(bool $ssl = true): static
    {
        $this->_ssl = $ssl;
        return $this;
    }

    public function url(string $url): static
    {
        $this->_url = $url;
        return $this;
    }

    public function method(string $method): static
    {
        $this->_method = $method;
        return $this;
    }

    public function data($data): static
    {
        $this->_data = $data;
        return $this;
    }

    public function query($query): static
    {
        $this->_query = $query;
        return $this;
    }

    public function host($host): static
    {
        $this->_host = $host;
        return $this;
    }

    public function getResponseHeader($key)
    {
        return $this->responseHeaders[$key] ?? null;
    }

    public function get_url(): string
    {
        return $this->_url;
    }

    public function get_method(): string
    {
        return $this->_method;
    }

    public function get_data()
    {
        return $this->_data;
    }

    public function get_query()
    {
        return $this->_query;
    }

    public function get_host(): string
    {
        return $this->_host;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }

    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    private static function defaults(): array
    {
        return array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 1,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_USERAGENT => "leveon-connector/0.1.8",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
       );
    }


}