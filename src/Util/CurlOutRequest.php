<?php

namespace Leveon\Connector\Util;


use CurlHandle;

class CurlOutRequest
{
    private static ?CurlHandle $ch = null;
    private string $_url;
    private string $_method = "GET";
    private $_data;
    private $_query;
    private bool $_ssl = false;
    private string $_host;
    private array $_headers = [];

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

    public static function closeConnection(): void
    {
        if (self::$ch !== null) curl_close(self::$ch);
        self::$ch = null;
    }

    public function do(): CurlOutResponse
    {
        if(self::$ch===null) self::$ch = curl_init();
        else curl_reset(self::$ch);
        $protocol = $this->_ssl ? "https" : "http";
        $qs = $this->_query ? '?' . http_build_query($this->_query) : '';
        $url = $protocol . '://' . $this->_host . $this->_url . $qs;
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        curl_setopt_array(self::$ch, $this->defaults());
        if ($this->_ssl) {
            curl_setopt_array(self::$ch, [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_VERBOSE => 1
            ]);
        }
        if ($this->_method === "POST") {
            curl_setopt_array(self::$ch, [
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $this->_data,
            ]);
        } elseif ($this->_method !== "GET") {
            curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, strtoupper($this->_method));
            curl_setopt_array(self::$ch, [
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $this->_data,
            ]);
        }
        if (count($this->_headers) > 0) {
            $h = [];
            foreach ($this->_headers as $n => $v) {
                $h[] = "{$n}: {$v}";
            }
            curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $h);
        }
        return CurlOutResponse::Parse(self::$ch, curl_exec(self::$ch));
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