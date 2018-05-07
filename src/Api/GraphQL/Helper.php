<?php
namespace Bybzmt\Blog\Api\GraphQL;

use GraphQL\Server\Helper as Base;
use GraphQL\Server\RequestError;


class Helper extends Base
{
    private $_ctx;

    public function __construct($ctx)
    {
        $this->_ctx = $ctx;
    }

    public function parseHttpRequest(callable $readRawBodyFn = null)
    {
        $method = isset($this->_ctx->request->server['request_method']) ? $this->_ctx->request->server['request_method'] : null;
        $bodyParams = [];
        $urlParams = $_GET;

        if ($method === 'POST') {
            $contentType = isset($this->_ctx->request->server['content_type']) ? $this->_ctx->request->server['content_type'] : null;

            if (stripos($contentType, 'application/graphql') !== false) {
                $rawBody =  $readRawBodyFn ? $readRawBodyFn() : $this->readRawBody();
                $bodyParams = ['query' => $rawBody ?: ''];
            } else if (stripos($contentType, 'application/json') !== false) {
                $rawBody = $readRawBodyFn ? $readRawBodyFn() : $this->readRawBody();
                $bodyParams = json_decode($rawBody ?: '', true);

                if (json_last_error()) {
                    throw new RequestError("Could not parse JSON: " . json_last_error_msg());
                }
                if (!is_array($bodyParams)) {
                    throw new RequestError(
                        "GraphQL Server expects JSON object or array, but got " .
                        Utils::printSafeJson($bodyParams)
                    );
                }
            } else if (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
                $bodyParams = $_POST;
            } else if (null === $contentType) {
                throw new RequestError('Missing "Content-Type" header');
            } else {
                throw new RequestError("Unexpected content type: " . Utils::printSafeJson($contentType));
            }
        }

        return $this->parseRequestParams($method, $bodyParams, $urlParams);
    }

    private function readRawBody()
    {
        return $this->_ctx->request->rawContent();
    }

    public function emitResponse($jsonSerializable, $httpStatus, $exitWhenDone)
    {
        $this->_ctx->response->status($httpStatus);
        $this->_ctx->response->header('Content-Type', 'application/json');
        $this->_ctx->response->end(json_encode($jsonSerializable));
    }

}
