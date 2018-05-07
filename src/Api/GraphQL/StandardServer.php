<?php
namespace Bybzmt\Blog\Api\GraphQL;

use GraphQL\Server\ServerConfig;
use GraphQL\Error\FormattedError;
use GraphQL\Error\InvariantViolation;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Executor\Promise\Promise;
use GraphQL\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;


class StandardServer
{
    /**
     * @var ServerConfig
     */
    private $config;

    /**
     * @var Helper
     */
    private $helper;

    public function send500Error($error, $debug = false, $exitWhenDone = false)
    {
        $response = [
            'errors' => [
                FormattedError::createFromException($error, $debug)
            ]
        ];
        $this->helper->emitResponse($response, 500, $exitWhenDone);
    }

    public function __construct($config)
    {
        if (is_array($config)) {
            $config = ServerConfig::create($config);
        }
        if (!$config instanceof ServerConfig) {
            throw new InvariantViolation("Expecting valid server config, but got " . Utils::printSafe($config));
        }
        $this->config = $config;
        $this->helper = new Helper($config->getContext());
    }

    public function handleRequest($parsedBody = null, $exitWhenDone = false)
    {
        $result = $this->executeRequest($parsedBody);
        $this->helper->sendResponse($result, $exitWhenDone);
    }

    public function executeRequest($parsedBody = null)
    {
        if (null === $parsedBody) {
            $parsedBody = $this->helper->parseHttpRequest();
        }

        if (is_array($parsedBody)) {
            return $this->helper->executeBatch($this->config, $parsedBody);
        } else {
            return $this->helper->executeOperation($this->config, $parsedBody);
        }
    }

    public function processPsrRequest(
        ServerRequestInterface $request,
        ResponseInterface $response,
        StreamInterface $writableBodyStream
    )
    {
        $result = $this->executePsrRequest($request);
        return $this->helper->toPsrResponse($result, $response, $writableBodyStream);
    }

    public function executePsrRequest(ServerRequestInterface $request)
    {
        $parsedBody = $this->helper->parsePsrRequest($request);
        return $this->executeRequest($parsedBody);
    }

    public function getHelper()
    {
        return $this->helper;
    }

}
