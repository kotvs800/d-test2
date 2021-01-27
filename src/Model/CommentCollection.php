<?php

declare(strict_types=1);

namespace ExampleClient\Model;

/**
 * Class CommentCollection
 *
 * @package ExampleClient\Model
 */
class CommentCollection implements CommentCollectionInterface
{
    /**
     * @var array
     */
    private array $storage = [];

    /**
     * @param CommentInterface $comment
     */
    public function add(CommentInterface $comment): void
    {
        $this->storage[] = $comment;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->storage);
    }

    /**
     * @param array $data
     * @return static
     */
    public static function createFromArray(array $data): self
    {
        $commentCollection = new static();

        if (!empty($data)) {
            foreach ($data as $row) {
                $commentCollection->storage[] = Comment::createFromArray($row);
            }
        }

        return $commentCollection;
    }
}
