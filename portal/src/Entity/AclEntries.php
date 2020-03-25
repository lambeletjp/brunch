<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AclEntries
 *
 * @ORM\Table(name="acl_entries", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4", columns={"class_id", "object_identity_id", "field_name", "ace_order"})}, indexes={@ORM\Index(name="IDX_46C8B806DF9183C9", columns={"security_identity_id"}), @ORM\Index(name="IDX_46C8B806EA000B10", columns={"class_id"}), @ORM\Index(name="IDX_46C8B8063D9AB4A6", columns={"object_identity_id"}), @ORM\Index(name="IDX_46C8B806EA000B103D9AB4A6DF9183C9", columns={"class_id", "object_identity_id", "security_identity_id"})})
 * @ORM\Entity
 */
class AclEntries
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
     * @var string|null
     *
     * @ORM\Column(name="field_name", type="string", length=50, nullable=true)
     */
    private $fieldName;

    /**
     * @var int
     *
     * @ORM\Column(name="ace_order", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $aceOrder;

    /**
     * @var int
     *
     * @ORM\Column(name="mask", type="integer", nullable=false)
     */
    private $mask;

    /**
     * @var bool
     *
     * @ORM\Column(name="granting", type="boolean", nullable=false)
     */
    private $granting;

    /**
     * @var string
     *
     * @ORM\Column(name="granting_strategy", type="string", length=30, nullable=false)
     */
    private $grantingStrategy;

    /**
     * @var bool
     *
     * @ORM\Column(name="audit_success", type="boolean", nullable=false)
     */
    private $auditSuccess;

    /**
     * @var bool
     *
     * @ORM\Column(name="audit_failure", type="boolean", nullable=false)
     */
    private $auditFailure;

    /**
     * @var \AclObjectIdentities
     *
     * @ORM\ManyToOne(targetEntity="AclObjectIdentities")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="object_identity_id", referencedColumnName="id")
     * })
     */
    private $objectIdentity;

    /**
     * @var \AclSecurityIdentities
     *
     * @ORM\ManyToOne(targetEntity="AclSecurityIdentities")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="security_identity_id", referencedColumnName="id")
     * })
     */
    private $securityIdentity;

    /**
     * @var \AclClasses
     *
     * @ORM\ManyToOne(targetEntity="AclClasses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_id", referencedColumnName="id")
     * })
     */
    private $class;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    public function setFieldName(?string $fieldName): self
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    public function getAceOrder(): ?int
    {
        return $this->aceOrder;
    }

    public function setAceOrder(int $aceOrder): self
    {
        $this->aceOrder = $aceOrder;

        return $this;
    }

    public function getMask(): ?int
    {
        return $this->mask;
    }

    public function setMask(int $mask): self
    {
        $this->mask = $mask;

        return $this;
    }

    public function getGranting(): ?bool
    {
        return $this->granting;
    }

    public function setGranting(bool $granting): self
    {
        $this->granting = $granting;

        return $this;
    }

    public function getGrantingStrategy(): ?string
    {
        return $this->grantingStrategy;
    }

    public function setGrantingStrategy(string $grantingStrategy): self
    {
        $this->grantingStrategy = $grantingStrategy;

        return $this;
    }

    public function getAuditSuccess(): ?bool
    {
        return $this->auditSuccess;
    }

    public function setAuditSuccess(bool $auditSuccess): self
    {
        $this->auditSuccess = $auditSuccess;

        return $this;
    }

    public function getAuditFailure(): ?bool
    {
        return $this->auditFailure;
    }

    public function setAuditFailure(bool $auditFailure): self
    {
        $this->auditFailure = $auditFailure;

        return $this;
    }

    public function getObjectIdentity(): ?AclObjectIdentities
    {
        return $this->objectIdentity;
    }

    public function setObjectIdentity(?AclObjectIdentities $objectIdentity): self
    {
        $this->objectIdentity = $objectIdentity;

        return $this;
    }

    public function getSecurityIdentity(): ?AclSecurityIdentities
    {
        return $this->securityIdentity;
    }

    public function setSecurityIdentity(?AclSecurityIdentities $securityIdentity): self
    {
        $this->securityIdentity = $securityIdentity;

        return $this;
    }

    public function getClass(): ?AclClasses
    {
        return $this->class;
    }

    public function setClass(?AclClasses $class): self
    {
        $this->class = $class;

        return $this;
    }


}
