<?php

namespace Newageerp\SfBaseEntity\Object;

use Newageerp\SfBaseEntity\Interface\IUser;
use OpenApi\Annotations as OA;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

class BaseUser implements IUser
{
    /**
     * @ORM\Column (type="string")
     */
    protected string $firstName = '';
    /**
     * @ORM\Column (type="string")
     */
    protected string $lastName = '';
    /**
     * @ORM\Column (type="string")
     */
    protected string $position = '';
    /**
     * @ORM\Column (type="string")
     */
    protected string $phone = '';
    /**
     * @ORM\Column (type="text")
     * @OA\Property (type="string", format="text")
     */
    protected string $allowedIp = '';
    /**
     * @ORM\Column (type="text")
     * @OA\Property (type="string", format="text")
     */
    protected string $mailSignature = '';
    /**
     * @ORM\Column(type="string")
     */
    protected string $email = '';
    /**
     * @ORM\Column(type="string")
     */
    protected string $login = '';
    /**
     * @ORM\Column(type="string")
     */
    protected string $password = '';

    /**
     * @ORM\Column(type="string")
     */
    protected string $permissionGroup = '';
    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $superUser = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $disabled = false;

    /**
     * @return bool
     */
    public function getDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled(bool $disabled): void
    {
        $this->disabled = $disabled;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function setPlainPassword(string $plainPassword)
    {
        if ($plainPassword) {
            $this->setPassword(password_hash($plainPassword, PASSWORD_BCRYPT));
        }
    }

    /**
     * @OA\Property(type="string", format="password")
     * @return string
     */
    public function getPlainPassword(): string
    {
        return '';
    }

    /**
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    public function getScopes(): array
    {
        return [
            $this->getPermissionGroup()
        ];
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getAllowedIp(): string
    {
        return $this->allowedIp;
    }

    /**
     * @param string $allowedIp
     */
    public function setAllowedIp(string $allowedIp): void
    {
        $this->allowedIp = $allowedIp;
    }

    /**
     * @return string
     */
    public function getMailSignature(): string
    {
        return $this->mailSignature;
    }

    /**
     * @param string $mailSignature
     */
    public function setMailSignature(string $mailSignature): void
    {
        $this->mailSignature = $mailSignature;
    }

    /**
     * @return string
     */
    public function getPermissionGroup(): string
    {
        return $this->permissionGroup;
    }

    /**
     * @param string $permissionGroup
     */
    public function setPermissionGroup(string $permissionGroup): void
    {
        $this->permissionGroup = $permissionGroup;
    }

    /**
     * @return bool
     */
    public function getSuperUser(): bool
    {
        return $this->superUser;
    }

    /**
     * @param bool $superUser
     */
    public function setSuperUser(bool $superUser): void
    {
        $this->superUser = $superUser;
    }

    /**
     * @Ignore
     */
    public function get_ViewTitle(): string
    {
        return $this->getFullName();
    }
}