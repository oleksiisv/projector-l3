<?php
declare(strict_types=1);

namespace Svystunov\Projectorl3;

use stdClass;
use Unirest\Request;
use Unirest\Response;

class Receiver
{
    const ENDPOINT_URL = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchangenew';
    const DATE_FORMAT = 'Ymd';
    const CURRENCY = 'USD';
    const RESPONSE_FORMAT = 'json';

    /**
     * @return false|string
     */
    public function getCurrencyRate()
    {
        $request = self::ENDPOINT_URL . $this->prepareRequest();
        $response = Request::get($request, $this->headersBuilder());

        return $this->responseConverter($response);
    }

    /**
     * @return string
     */
    private function prepareRequest(): string
    {
        return sprintf(
            '?%s&valcode=%s&date=%s',
            self::RESPONSE_FORMAT,
            self::CURRENCY,
            $this->getCurrentDate()
        );
    }

    /**
     * @return string
     */
    private function getCurrentDate(): string
    {
        return date(self::DATE_FORMAT);
    }

    /**
     * @return string[]
     */
    private function headersBuilder()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * @param Response $response
     *
     * @return int|null
     */
    private function responseConverter(Response $response): ?int
    {
        $result = $response->body;
        $rateObject = !empty($result) ? array_pop($result) : new stdClass();

        return isset($rateObject->rate) ? (int)($rateObject->rate * 10000) : null;
    }
}