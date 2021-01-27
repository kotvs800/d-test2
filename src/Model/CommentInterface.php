<?php

declare(strict_types=1);

namespace ExampleClient\Model;

/**
 * Interface CommentInterface
 *
 * @package ExampleClient\Model
 */
interface CommentInterface extends \JsonSerializable
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): self;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * @return string
     */
    public function getText(): string;

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText(string $text): self;

    /**
     * @param array $data
     * @return static
     */
    public static function createFromArray(array $data): self;
}
