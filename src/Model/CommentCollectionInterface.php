<?php

declare(strict_types=1);

namespace ExampleClient\Model;

/**
 * Interface CommentCollectionInterface
 *
 * @package ExampleClient\Model
 */
interface CommentCollectionInterface extends \IteratorAggregate
{
    /**
     * @param CommentInterface $comment
     */
    public function add(CommentInterface $comment): void;

    /**
     * @param array $data
     * @return static
     */
    public static function createFromArray(array $data): self;
}