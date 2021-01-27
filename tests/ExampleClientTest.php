<?php

declare(strict_types=1);

namespace Tests\ExampleClient;

use ExampleClient\ExampleClient;
use ExampleClient\Model\CommentCollectionInterface;
use ExampleClient\Model\CommentInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class ExampleClientTest
 * @package Tests\ExampleClient
 */
class ExampleClientTest extends TestCase
{
    public function getExampleClientMock()
    {
        $client = $this->createStub(ClientInterface::class);
        $requestFactory = $this->createStub(RequestFactoryInterface::class);
        $streamFactory = $this->createStub(StreamFactoryInterface::class);
        $domain = 'http://example.com';

        return $this->getMockBuilder(ExampleClient::class)
            ->setConstructorArgs(
                [
                    $client,
                    $requestFactory,
                    $streamFactory,
                    $domain,
                ]
            )
            ->onlyMethods(['request'])
            ->getMock();
    }

    /**
     * @dataProvider errorFormatCommentsProvider
     */
    public function testGetCommentsError($case)
    {
        $mock = $this->getExampleClientMock();

        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')->willReturn($case);

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $mock->expects($this->any())->method('request')->willReturn($response);
        $this->expectException(\Exception::class);
        $mock->getComments();
    }


    public function errorFormatCommentsProvider()
    {
        return [
            [''],
            ['{}'],
            ['{"items":[]}'],
            ['{"comments":[]}'],
        ];
    }

    public function testGetCommentSuccess()
    {
        $mock = $this->getExampleClientMock();
        $jsonResponse = '{"comments": [{"id": 1, "name": "First Comment", "text": "Comment Text"}]}';

        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')->willReturn($jsonResponse);

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $mock->expects($this->any())->method('request')->willReturn($response);
        $comments = $mock->getComments();
        $this->assertInstanceOf(CommentCollectionInterface::class, $comments);
    }

    /**
     * @dataProvider errorFormatCommentProvider
     */
    public function testCreateCommentError($case)
    {
        $mock = $this->getExampleClientMock();
        $commentStub = $this->createStub(CommentInterface::class);

        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')->willReturn($case);

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $mock->expects($this->any())->method('request')->willReturn($response);
        $this->expectException(\Exception::class);
        $mock->createComment($commentStub);
    }

    public function errorFormatCommentProvider()
    {
        return [
            [''],
            ['{}'],
            ['{"item":{}}'],
            ['{"comment":{}}'],
        ];
    }

    public function testCreateCommentSuccess()
    {
        $mock = $this->getExampleClientMock();
        $jsonResponse = '{"comment": {"id": 1, "name": "First Comment", "text": "Comment Text"}}';
        $commentStub = $this->createStub(CommentInterface::class);

        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')->willReturn($jsonResponse);

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $mock->expects($this->any())->method('request')->willReturn($response);
        $comment = $mock->createComment($commentStub);
        $this->assertInstanceOf(CommentInterface::class, $comment);
        $this->assertIsInt($comment->getId());
        $this->assertEquals('First Comment', $comment->getName());
        $this->assertEquals('Comment Text', $comment->getText());
    }

    /**
     * @dataProvider errorFormatCommentProvider
     */
    public function testUpdateCommentError($case)
    {
        $mock = $this->getExampleClientMock();
        $commentStub = $this->createStub(CommentInterface::class);

        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')->willReturn($case);

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $mock->expects($this->any())->method('request')->willReturn($response);
        $this->expectException(\Exception::class);
        $mock->updateComment($commentStub);
    }

    public function testUpdateCommentSuccess()
    {
        $mock = $this->getExampleClientMock();
        $jsonResponse = '{"comment": {"id": 1, "name": "First Comment", "text": "Comment Text"}}';
        $commentStub = $this->createStub(CommentInterface::class);

        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')->willReturn($jsonResponse);

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $mock->expects($this->any())->method('request')->willReturn($response);
        $comment = $mock->updateComment($commentStub);
        $this->assertInstanceOf(CommentInterface::class, $comment);
        $this->assertEquals(1, $comment->getId());
        $this->assertEquals('First Comment', $comment->getName());
        $this->assertEquals('Comment Text', $comment->getText());
    }

    public function testRequestSuccess()
    {
        $mock = $this->getExampleClientMock();

        $request = $this->createStub(RequestInterface::class);
        $response = $mock->request($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}