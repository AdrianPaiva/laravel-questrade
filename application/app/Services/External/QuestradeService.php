<?php
namespace App\Services\External;

use App\Exceptions\QuestradeAuthorizationException;
use App\Models\External\ApiClient;
use App\Models\QuestradeCredential;
use App\Services\QuestradeCredentialService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use GuzzleHttp\Promise\Promise;

class QuestradeService extends ApiService
{
    public $questrade_credential_service;
    protected $questrade_credential;

    public function __construct(QuestradeCredentialService $questrade_credential_service, ?QuestradeCredential $questrade_credential, $version = 'v1')
    {
        if (!$questrade_credential) {
            throw new QuestradeAuthorizationException("Unable to find your Questrade Credentials, please connect your account");
        }
        
        parent::__construct($questrade_credential->access_token, $questrade_credential->api_server, $version);

        $this->questrade_credential_service = $questrade_credential_service;
        $this->setQuestradeCredential($questrade_credential);
    }

    public function getQuestradeCredential()
    {
        return $this->questrade_credential;
    }
    
    public function setQuestradeCredential(QuestradeCredential $questrade_credential)
    {
        if ($questrade_credential->isExpired()) {
            $questrade_credential = $this->reauthorize($questrade_credential);
        }

        $this->questrade_credential = $questrade_credential;
    }

    /**
     * Refresh the access_token
     *
     * @param  QuestradeCredential $questrade_credential
     * @return QuestradeCredential
     *
     * @throws  QuestradeAuthorizationException
     *
     */
    public function reauthorize(QuestradeCredential $questrade_credential): QuestradeCredential
    {
        $response = $this->client->request(
            'POST',
            'https://login.questrade.com/oauth2/token',
            [
                'query' => [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $questrade_credential->refresh_token
                ]
            ]
        );

        if (!$response->wasSuccessful()) {
            $this->questrade_credential_service->delete($questrade_credential);

            throw new QuestradeAuthorizationException("Error Reconnecting your Questrade Account");
        }

        $updated_credential = $this->questrade_credential_service->update($questrade_credential, $response->getContent()->only([
            'access_token',
            'token_type',
            'expire_in',
            'refresh_token',
            'api_server',
        ])->toArray());

        $this->setClient(new ApiClient($updated_credential->access_token));

        return $updated_credential;
    }

    /**
     * Get Accounts
     *
     * @return Collection
     */
    public function getAccounts(): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "accounts"
        );

        return $response->getContent();
    }

    /**
     * Get Account Positions
     *
     * @param  $account_number
     *
     * @return Collection
     */
    public function getAccountPositions(int $account_number): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "accounts/{$account_number}/positions"
        );

        return $response->getContent();
    }

    /**
     * Get Account Balances
     *
     * @param  $account_number
     *
     * @return Collection
     */
    public function getAccountBalances(int $account_number): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "accounts/{$account_number}/balances"
        );

        return $response->getContent();
    }

    /**
     * Get Account Executions
     *
     * @param  $account_number
     *
     * @return Collection
     */
    public function getAccountExecutions(int $account_number): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "accounts/{$account_number}/executions"
        );

        return $response->getContent();
    }

    /**
     * Get Account Orders
     *
     * @param  $account_number
     *
     * @return Collection
     */
    public function getAccountOrders(int $account_number): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "accounts/{$account_number}/orders"
        );

        return $response->getContent();
    }

    /**
     * Get Account Activities
     *
     * @param  $account_number
     *
     * @return Collection
     */
    public function getAccountActivities(int $account_number, Carbon $start_date, Carbon $end_date): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "accounts/{$account_number}/activities",
            [
                'query' => [
                    'startTime' => $start_date->format("c"),
                    'endTime'   => $end_date->format("c"),
                ],
            ]
        );

        return $response->getContent();
    }

    /**
     * Get Account Activities
     *
     * @param  $account_number
     *
     * @return Collection
     */
    public function getAccountActivitiesAsync(int $account_number, Carbon $start_date, Carbon $end_date): Promise
    {
        return $this->client->requestAsync(
            'GET',
            $this->getCompleteUrl() . "accounts/{$account_number}/activities",
            [
                'query' => [
                    'startTime' => $start_date->format("c"),
                    'endTime'   => $end_date->format("c"),
                ],
            ]
        );
    }

    /**
     * @param  string $account_number
     * @param  int|integer $number_of_months
     * @return Collection                    [multiple ApiResponses]
     */
    public function getBulkAccountActivities(string $account_number, int $number_of_months = 12): Collection
    {
        $promises = [];

        $end_date = Carbon::now();
        $start_date = Carbon::now()->subDays(30);

        for ($i = 0; $i < $number_of_months; $i++) { 
            $promises[] = $this->getAccountActivitiesAsync($account_number, $start_date, $end_date);

            $start_date->subDays(30);
            $end_date->subDays(30);
        }

        return ApiClient::parsePromises($promises);
    }

    /**
     * Get One Symbol
     *
     * @param  $symbol_id
     *
     * @return Collection
     */
    public function getSymbol(int $symbol_id): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "symbols/{$symbol_id}"
        );

        return $response->getContent();
    }

    /**
     * Search Symbols
     *
     * @param  string $prefix   Prefix of a symbol or any word in the description.
     * @param  int $offset      Offset in number of records from the beginning of a result set.
     *
     * @return Collection
     */
    public function searchSymbols(string $prefix, int $offset = 0): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "symbols/search",
            [
                'query' => [
                    'prefix' => $prefix,
                    'offset' => $offset,
                ],
            ]
        );

        return $response->getContent();
    }

    /**
     * Get Symbol Options
     *
     * @param  $symbol_id
     *
     * @return Collection
     */
    public function getSymbolOptions(int $symbol_id): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "symbols/{$symbol_id}/options"
        );

        return $response->getContent();
    }

    /**
     * Retrieves information about supported markets.
     *
     * @return Collection
     */
    public function getMarkets(): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "markets"
        );

        return $response->getContent();
    }

    /**
     * Retrieves a single Level 1 market data quote for one or more symbols.
     *
     * @return Collection
     */
    public function getMarketQuotes($symbol_ids): Collection
    {
        if (is_array($symbol_ids)) {
            $symbol_ids = implode(',', $symbol_ids);
        }

        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "markets/quotes",
            [
                'query' => [
                    'ids' => $symbol_ids,
                ],
            ]

        );

        return $response->getContent();
    }

    /**
     * Retrieves historical market data in the form of OHLC candlesticks for a specified symbol.
     *
     * / This call is limited to returning 2,000 candlesticks in a single response.
     *
     * @return Collection
     */
    public function getMarketCandles(string $symbol_id, Carbon $start_time, Carbon $end_time, $interval): Collection
    {
        $response = $this->client->request(
            'GET',
            $this->getCompleteUrl() . "markets/candles/{$symbol_id}",
            [
                'query' => [
                    'ids' => $symbol_ids,
                ],
            ]

        );

        return $response->getContent();
    }
}
