<?php

declare(strict_types=1);

namespace ExampleClient\Model;

/**
 * Class Comment
 *
 * @package ExampleClient\Model
 */
class Comment implements CommentInterface
{
    /**
     * @var int
     */
    protected int $id;
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var string
     */
    protected string $text;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return CommentInterface
     */
    public function setId(int $id): CommentInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return CommentInterface
     */
    public function setName(string $name): CommentInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return CommentInterface
     */
    public function setText(string $text): CommentInterface
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'text' => $this->text,
        ];
    }

    /**
     * @param array $data
     * @return static
     */
    public static function createFromArray(array $data): self
    {
        $comment = new static();
        $comment->id = (int)$data['id'];
        $comment->name = (string)($data['name'] ?? '');
        $comment->text = (string)($data['text'] ?? '');

        return $comment;
    }
}