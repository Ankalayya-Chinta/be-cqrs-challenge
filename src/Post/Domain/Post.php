<?php

declare(strict_types=1);

namespace App\Post\Domain;

use App\Shared\Domain\AggregateRoot;
use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'post')]
class Post extends AggregateRoot implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(length: 20)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $summary;

    // Feature #1
    #[ORM\Column(length: 255)]
    private string $description;

    public function __construct(string $id, string $title, string $summary, string $description)
    {
        $this->id = Uuid::fromString($id);
        $this->title = $title;
        $this->summary = $summary;
        $this->description = $description; // Feature #1
    }

    public static function new(string $id, string $title, string $summary, string $description): self
    {
        Assert::that($id)->uuid();
        Assert::that($title)->maxLength(20)->notBlank();
        Assert::that($summary)->maxLength(255)->notBlank();
        Assert::that($description)->maxLength(255)->notBlank(); // Feature #1

        return new self($id, $title, $summary, $description);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function jsonSerialize()
        {
            return array(
                'post_id' => $this->id,
                'title' => $this->title,
                'summary'=> $this->summary,
            );
        }
}
