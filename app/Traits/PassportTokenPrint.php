<?php

namespace App\Traits;

use App\Models\User;
use DateTime;
use GuzzleHttp\Psr7\Response;
use Illuminate\Events\Dispatcher;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\Client;
use Laravel\Passport\Bridge\RefreshToken;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;

# All glory goes to this person @ this comment https://github.com/laravel/passport/issues/71#issuecomment-330506407
# I just did some modifications in order to fetch the tokens from the database and not generate new ones each time.

/**
 * Trait PassportToken
 *
 * @package App\Traits
 */
trait PassportTokenPrint
{

    protected function fetchPassportTokenByUser(User $user, $clientId, $token)
    {
        $accessToken = new AccessToken($user->id, [], new Client($clientId, null, null));
        $accessToken->setIdentifier($token->id);
        $accessToken->setExpiryDateTime(new DateTime($token->expires_at));

        $refreshToken = new RefreshToken();

        $refreshToken->setAccessToken($accessToken);
        $refreshToken->setExpiryDateTime(new DateTime($token->expires_at));

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    protected function sendBearerTokenResponse($accessToken, $refreshToken)
    {
        $response = new BearerTokenResponse();
        $response->setAccessToken($accessToken);
        $response->setRefreshToken($refreshToken);

        $privateKey = new CryptKey('file://' . Passport::keyPath('oauth-private.key'));

        $response->setPrivateKey($privateKey);
        $response->setEncryptionKey(app('encrypter')->getKey());

        return $response->generateHttpResponse(new Response);
    }

    /**
     * @param \App\Models\User $user
     * @param $clientId
     * @param bool $output default = true
     * @return array | \League\OAuth2\Server\ResponseTypes\BearerTokenResponse
     */
    protected function fetchAccessTokenForClient(User $user, $clientId, $token, $output = true)
    {
        $passportToken = $this->fetchPassportTokenByUser($user, $clientId, $token);

        $bearerToken = $this->sendBearerTokenResponse($passportToken['access_token'], $passportToken['refresh_token']);

        if (!$output) {
            $bearerToken = json_decode($bearerToken->getBody()->__toString(), true);
        }

        return $bearerToken;
    }


    protected function clientAccessToken(User $user, $clientId, $token){
        return $this->fetchAccessTokenForClient($user, $clientId, $token, false)['access_token'];
    }


}