<?php

declare(strict_types=1);

namespace ExampleClient;

use ExampleClient\Model\CommentCollectionInterface;
use ExampleClient\Model\CommentInterface;

/**
 * Interface ExampleClientInterface
 * @package ExampleClient
 */
interface ExampleClientInterface
{
    /**
     * @return CommentCollectionInterface
     */
    public function getComments(): CommentCollectionInterface;

    /**
     * @param CommentInterface $comment
     * @return CommentInterface
     */
    public function createComment(CommentInterface $comment): CommentInterface;

    /**
     * @param CommentInterface $comment
     * @return CommentInterface
     */
    public function updateComment(CommentInterface $comment): CommentInterface;
}
