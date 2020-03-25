<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AclSecurityIdentities
 *
 * @ORM\Table(name="acl_security_identities", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_8835EE78772E836AF85E0677", columns={"identifier", "username"})})
 * @ORM\Entity
 */
class AclSecurityIdentities
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=200, nullable=false)
     */
    private $identifier;

    /**
     * @var bool
     *
     * @ORM\Column(name="username", type="boolean", nullable=false)
     */
    private $username;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getUsername(): ?bool
    {
        return $this->username;
    }

    public function setUsername(bool $username): self
    {
        $this->username = $username;

        return $this;
    }


}
