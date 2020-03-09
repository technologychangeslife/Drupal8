<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Drupal\tmgmt_contentapi\Swagger\Client\ApiException;
use Drupal\tmgmt_contentapi\Swagger\Client\Configuration;
use Drupal\tmgmt_contentapi\Swagger\Client\HeaderSelector;
use Drupal\tmgmt_contentapi\Swagger\Client\ObjectSerializer;

/**
 * TokenApi Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class TokenApi {
  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * @var \Drupal\tmgmt_contentapi\Swagger\Client\Configuration
   */
  protected $config;

  /**
   * @param \GuzzleHttp\ClientInterface $client
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Configuration $config
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\HeaderSelector $selector
   */
  public function __construct(
        ClientInterface $client = NULL,
        Configuration $config = NULL,
        HeaderSelector $selector = NULL
    ) {
    $this->client = $client ?: new Client();
    $this->config = $config ?: new Configuration();
    $this->headerSelector = $selector ?: new HeaderSelector();
  }

  /**
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Configuration
   */
  public function getConfig() {
    return $this->config;
  }

  /**
   * Operation oauth2TokenPost.
   *
   * Get a new token.
   *
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateToken $body
   *   Created token object (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\Token
   */
  public function oauth2TokenPost($body) {
    list($response) = $this->oauth2TokenPostWithHttpInfo($body);
    return $response;
  }

  /**
   * Operation oauth2TokenPostWithHttpInfo.
   *
   * Get a new token.
   *
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateToken $body
   *   Created token object (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\Token, HTTP status code, HTTP response headers (array of strings)
   */
  public function oauth2TokenPostWithHttpInfo($body) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Token';
    $request = $this->oauth2TokenPostRequest($body);

    try {
      $options = $this->createHttpClientOption();
      try {
        $response = $this->client->send($request, $options);
      }
      catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $e->getResponse() ? $e->getResponse()->getHeaders() : NULL,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : NULL
          );
      }

      $statusCode = $response->getStatusCode();

      if ($statusCode < 200 || $statusCode > 299) {
        throw new ApiException(
        sprintf(
            '[%d] Error connecting to the API (%s)',
            $statusCode,
            $request->getUri()
        ),
        $statusCode,
        $response->getHeaders(),
        $response->getBody()
        );
      }

      $responseBody = $response->getBody();
      if ($returnType === '\SplFileObject') {
        // Stream goes to serializer.
        $content = $responseBody;
      }
      else {
        $content = $responseBody->getContents();
        if ($returnType !== 'string') {
          $content = json_decode($content);
        }
      }

      return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
    ];

    }
    catch (ApiException $e) {
      switch ($e->getCode()) {
        case 201:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Token',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 400:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 500:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;
      }
      throw $e;
    }
  }

  /**
   * Operation oauth2TokenPostAsync.
   *
   * Get a new token.
   *
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateToken $body
   *   Created token object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function oauth2TokenPostAsync($body) {
    return $this->oauth2TokenPostAsyncWithHttpInfo($body)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation oauth2TokenPostAsyncWithHttpInfo.
   *
   * Get a new token.
   *
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateToken $body
   *   Created token object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function oauth2TokenPostAsyncWithHttpInfo($body) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Token';
    $request = $this->oauth2TokenPostRequest($body);

    return $this->client
      ->sendAsync($request, $this->createHttpClientOption())
      ->then(
            function ($response) use ($returnType) {
                $responseBody = $response->getBody();
              if ($returnType === '\SplFileObject') {
                // Stream goes to serializer.
                $content = $responseBody;
              }
              else {
                  $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                  $content = json_decode($content);
                }
              }

                return [
                    ObjectSerializer::deserialize($content, $returnType, []),
                    $response->getStatusCode(),
                    $response->getHeaders()
                ];
            },
            function ($exception) {
                $response = $exception->getResponse();
                $statusCode = $response->getStatusCode();
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $exception->getRequest()->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }
        );
  }

  /**
   * Create request for operation 'oauth2TokenPost'.
   *
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateToken $body
   *   Created token object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function oauth2TokenPostRequest($body) {
    // Verify the required parameter 'body' is set.
    if ($body === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $body when calling oauth2TokenPost'
      );
    }

    $resourcePath = '/oauth2/token';
    $formParams = [];
    $queryParams = [];
    $headerParams = [];
    $httpBody = '';
    $multipart = FALSE;

    // Body params.
    $_tempBody = NULL;
    if (isset($body)) {
      $_tempBody = $body;
    }

    if ($multipart) {
      $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
      );
    }
    else {
      $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        ['application/json']
        );
    }

    // For model (json/xml)
    if (isset($_tempBody)) {
      // $_tempBody is the method argument, if present.
      $httpBody = $_tempBody;
      // \stdClass has no __toString(), so we should encode it manually.
      if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
      }
    }
    elseif (count($formParams) > 0) {
      if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
          $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
    ];
        }
        // For HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

      }
      elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

      }
      else {
        // For HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
      }
    }

    $defaultHeaders = [];
    if ($this->config->getUserAgent()) {
      $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
    }

    $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
    );

    $query = \GuzzleHttp\Psr7\build_query($queryParams);
    return new Request(
        'POST',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
    );
  }

  /**
   * Create http client option.
   *
   * @throws \RuntimeException on file opening failure
   *
   * @return array of http client options
   */
  protected function createHttpClientOption() {
    $options = [];
    if ($this->config->getDebug()) {
      $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
      if (!$options[RequestOptions::DEBUG]) {
        throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
      }
    }

    return $options;
  }

}
