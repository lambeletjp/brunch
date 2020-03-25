<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Thread
 *
 * @ORM\Table(name="thread")
 * @ORM\Entity
 */
class Thread
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", length=255, nullable=false)
     */
    private $permalink;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_commentable", type="boolean", nullable=false)
     */
    private $isCommentable;

    /**
     * @var int
     *
     * @ORM\Column(name="num_comments", type="integer", nullable=false)
     */
    private $numComments;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_comment_at", type="datetime", nullable=true)
     */
    private $lastCommentAt;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPermalink(): ?string
    {
        return $this->permalink;
    }

    public function setPermalink(string $permalink): self
    {
        $this->permalink = $permalink;

        return $this;
    }

    public function getIsCommentable(): ?bool
    {
        return $this->isCommentable;
    }

    public function setIsCommentable(bool $isCommentable): self
    {
        $this->isCommentable = $isCommentable;

        return $this;
    }

    public function getNumComments(): ?int
    {
        return $this->numComments;
    }

    public function setNumComments(int $numComments): self
    {
        $this->numComments = $numComments;

        return $this;
    }

    public function getLastCommentAt(): ?\DateTimeInterface
    {
        return $this->lastCommentAt;
    }

    public function setLastCommentAt(?\DateTimeInterface $lastCommentAt): self
    {
        $this->lastCommentAt = $lastCommentAt;

        return $this;
    }


}
