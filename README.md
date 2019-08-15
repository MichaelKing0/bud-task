# Bud Task

## Part 1
I have implemented this in `app/DeathStar`. All of the functionality can be accessed through `DeathStarService` which should be the primary API for interacting with the Death Star for client code. Unit tests are in `tests/App/DeathStar`.

## Part 2
This required a small change to `DeathStarService` and an update to the response mocks. I've implemented the language parser behind `LanguageConverterInterface` so new languages can be added by clients easily.

## Installation
- Clone this repository
- From the project root, run `composer install`
- From the project root, run `vendor/bin/phpunit` to run the tests

## Design decisions

I have chosen to use the Lumen microframework as I am most familiar with Laravel. Out of the box it has a configured PHPUnit and service container. I prefer component based structuring and a framework agnostic approach, so all of the code is in app/DeathStar. It would be easy to move this into any framework.

The test document specifies that each connection to the API requires an OAuth token. To simplify this I have included a `setOAuthToken()` method on the `DeathStarService`. If this is set, the `DeathStarApiClient` will use it for all requests besides requesting new tokens.   

## Assumptions

- The OAuth spec recommends that client IDs and secrets are Base64 encoded however the test document appears to want these as a request body. I have gone with the OAuth recommendation Base64 encoded these values. I've also assumed that the grant type is `client_credentials` as it wasn't specified.   

- It isn't clear how client certificates are obtained. I've assumed these are generated client side. 
