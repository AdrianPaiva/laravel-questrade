<?php

namespace App\Socialite\Questrade;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'QUESTRADE';

    /**
     * {@inheritdoc}
     */
    protected $scopes = ['*'];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://login.questrade.com/oauth2/authorize',
            $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://login.questrade.com/oauth2/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        // $response = $this->getHttpClient()->get(
        //     'https://oauth.reddit.com/api/v1/me', [
        //     'headers' => [
        //         'Authorization' => 'Bearer '.$token,
        //         'User-Agent'    => $this->getUserAgent(),
        //     ],
        // ]);

        // return json_decode($response->getBody()->getContents(), true);
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'   => null,
            'nickname' => null,
            'name' => null,
            'email' => null,
            'avatar' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return [
            'grant_type'   => 'authorization_code',
            'code'         => $code,
            'redirect_uri' => $this->redirectUrl,
            'client_id'    => $this->clientId,
        ];
    }

    /**
     * {@inheritdoc}
     */
    // public static function additionalConfigKeys()
    // {
    //     return ['platform', 'app_id', 'version_string'];
    // }
}
