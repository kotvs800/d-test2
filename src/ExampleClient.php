<?php

declare(strict_types=1);

namespace ExampleClient;

use ExampleClient\Model\Comment;
use ExampleClient\Model\CommentCollection;
use ExampleClient\Model\CommentCollectionInterface;
use ExampleClient\Model\CommentInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Class ExampleClient
 *
 * @package ExampleClient
 */
class ExampleClient implements ExampleClientInterface
{
    /**
     * @var ClientInterface
     */
    private ClientInterface $client;

    /**
     * @var RequestFactoryInterface
     */
    private RequestFactoryInterface $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    private StreamFactoryInterface $streamFactory;

    /**
     * @var string
     */
    private string $baseUrl;

    /**
     * ExampleClient constructor.
     *
     * @param ClientInterface         $client
     * @param RequestFactoryInterface $requestFactory
     * @param StreamFactoryInterface  $streamFactory
     * @param string                  $baseUrl
     */
    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        string $baseUrl
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return CommentCollectionInterface
     * @throws \Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getComments(): CommentCollectionInterface
    {
        $request = $this->requestFactory->createRequest(
            'GET',
            $this->baseUrl . '/comments'
        );

        $response = $this->request($request);
        $responseBody = json_decode($response->getBody()->getContents(), true);

        if (empty($responseBody['comments']) || !is_array($responseBody['comments'])) {
            throw new \Exception('Response format wrong');
        }

        return CommentCollection::createFromArray($responseBody['comments']);
    }

    /**
     * @param CommentInterface $comment
     *
     * @return CommentInterface
     * @throws \Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function createComment(CommentInterface $comment): CommentInterface
    {
        $request = $this->requestFactory->createRequest(
            'POST',
            $this->baseUrl . '/comment'
        );
        $streamBody = $this->streamFactory->createStream(json_encode($comment));
        $request->withBody($streamBody);
        $response = $this->request($request);
        $responseBody = json_decode($response->getBody()->getContents(), true);

        if (empty($responseBody['comment'])) {
            throw new \Exception('Response format wrong');
        }

        return Comment::createFromArray($responseBody['comment']);;
    }

    /**
     * @param CommentInterface $comment
     *
     * @return CommentInterface
     * @throws \Exception
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function updateComment(CommentInterface $comment): CommentInterface
    {
        $request = $this->requestFactory->createRequest(
            'PUT',
            $this->baseUrl . '/comment/' . $comment->getId()
        );
        $streamBody = $this->streamFactory->createStream(json_encode($comment));
        $request->withBody($streamBody);
        $response = $this->request($request);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        if (empty($responseBody['comment'])) {
            throw new \Exception('Response format wrong');
        }

        return Comment::createFromArray($responseBody['comment']);
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Exception
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->client->sendRequest($request);
        } catch (\Exception $exception) {
            // TODO: желательно типизировать исключения и реализовать обработчик
            throw new \Exception($exception->getMessage());
        }

        return $response;
    }
}