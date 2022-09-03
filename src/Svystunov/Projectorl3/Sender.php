<?php
declare(strict_types=1);

namespace Svystunov\Projectorl3;

use Unirest\Request;
use Unirest\Response;
use Svystunov\Projectorl3\Auth;

class Sender
{
    const ENDPOINT_URL = 'http://www.google-analytics.com/collect';
    const ENDPOINT_URL_DEBUG = 'http://www.google-analytics.com/debug/collect';
    const EVENT_CATEGORY = 'nbu_currency_rate';
    const EVENT_ACTION = 'run';
    const EVENT_LABEL = 'USDCurrencyRate';
    const IS_DEBUG = false;

    /**
     * @param int $rate
     *
     * @return string
     */
    public function sendCurrencyRateToGa(int $rate): string
    {
        if (!$rate) {
            return 'Rate is missing';
        }
        try {
            $response = Request::post(
                sprintf('%s?%s', $this->getEndpointUrl(), $this->queryBuilder($rate)),
                $this->headersBuilder()
            );

            return $this->responseConverter($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return string
     */
    private function getEndpointUrl(): string
    {
        return self::IS_DEBUG ? self::ENDPOINT_URL_DEBUG : self::ENDPOINT_URL;
    }

    /**
     * @param int $rate
     *
     * @return string
     */
    private function queryBuilder(int $rate): string
    {
        $authData = $this->getAuthData();
        $params = [
            'v' => '1',
            't' => 'event',
            'tid' => $authData['tid'] ?? '',
            'cid' => $authData['cid'] ?? '',
            'ec' => self::EVENT_CATEGORY,
            'ea' => self::EVENT_ACTION,
            'el' => self::EVENT_LABEL,
            'ev' => $rate,
        ];

        return http_build_query($params);
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    private function getAuthData()
    {
        $authData = \Svystunov\Projectorl3\Auth::getAuthData();
        if (!$authData) {
            throw new \Exception('Auth data is not available');
        }
        $tid = $authData['analytics']['tid'] ?? null;
        $cid = $authData['analytics']['tid'] ?? null;
        if ($tid === null || $cid === null) {
            throw new \Exception('GA auth data is not available');
        }

        return $authData['analytics'] ?? [];
    }

    /**
     * @return string[]
     */
    private function headersBuilder(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * @param Response $response
     *
     * @return string
     */
    private function responseConverter(Response $response): string
    {
        return self::IS_DEBUG ? json_encode($response->body) : (string)$response->code;
    }
}