<?php

namespace Leveon\Connector\Util;

use CurlHandle;

class CurlOutResponse
{
    private int $code = 0;
    /**
     * @var string[]
     */
    private array $headers = [];
    private ?string $content = null;
    private bool $successful = false;
    private mixed $json = null;

    public function __construct(CurlHandle $ch, string $response){
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $this->parseHeaders($header);
        $this->content = substr($response, $header_size);
        $this->successful = intdiv($this->code, 100) === 2;
        if(!$this->successful){
            var_dump($response);
        }
    }

    public static function Parse(CurlHandle $ch, string $response): CurlOutResponse
    {
        return new self($ch, $response);
    }

    private function parseHeaders(string $headers): void
    {
        foreach (explode("\n", $headers) as $k => $v) {
            $t = explode(':', $v, 2);
            if (isset($t[1])) {
                $name = trim($t[0]);
                $value = trim($t[1]);
                if (!isset($this->headers[$name]))
                    $this->headers[$name] = $value;
                else
                    $this->headers[$name] .= "\n" . $value;
            } else {
                if (preg_match("#HTTP/[0-9.]+\s+([0-9]+)#", $v, $out)) {
                    $this->code = intval($out[1]);
                    $this->headers = [];
                }
            }
        }
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $key
     * @param null $default
     * @return string | null
     */
    public function getHeader(string $key, $default = null): ?string
    {
        return $this->headers[$key] ?? $default;
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function isFailed(): bool
    {
        return $this->successful === false;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function json(): mixed
    {
        if($this->content!==null && strlen($this->content)>0 && $this->json === null){
            $this->json = json_decode($this->content);
        }
        return $this->json;
    }

}