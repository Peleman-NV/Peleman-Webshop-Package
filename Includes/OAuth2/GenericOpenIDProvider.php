<?php

declare(strict_types=1);

namespace PWP\includes\OAuth2;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class GenericOpenIDProvider extends GenericProvider
{
    public function __construct(array $options, array $collaborators)
    {
        parent::__construct($options, $collaborators);

    }
    public function getBaseAuthorizationUrl()
    {
        return '';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return '';
    }

    public function getPublicKeyUrl(): string
    {
        return '';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return '';
    }

    public function getDefaultScopes(): array
    {
        return [];
    }

    public function checkResponse(ResponseInterface $response, $data): void
    {
    }

    public function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new GenericResourceOwner($response, $token->getResourceOwnerId());
    }
}
    