<?php
namespace Newageerp\SfContact\Object;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

abstract class IContactDetailsAbstract implements IContactDetails
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
     * @ORM\Column (type="json")
     * @OA\Property(type="array", @OA\Items(type="string"), additionalProperties={{"valueTransform":"phone"}})
     */
    protected array $phones = [];
    /**
     * @ORM\Column (type="json")
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    protected array $emails = [];
    /**
     * @ORM\Column(type="text")
     */
    protected string $description = '';

    /**
     * Get the value of firstName
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @param string $firstName
     *
     * @return self
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @param string $lastName
     *
     * @return self
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of position
     *
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Set the value of position
     *
     * @param string $position
     *
     * @return self
     */
    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get the value of phones
     *
     * @return array
     */
    public function getPhones(): array
    {
        return $this->phones;
    }

    /**
     * Set the value of phones
     *
     * @param array $phones
     *
     * @return self
     */
    public function setPhones(array $phones): self
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * Get the value of emails
     *
     * @return array
     */
    public function getEmails(): array
    {
        return $this->emails;
    }

    /**
     * Set the value of emails
     *
     * @param array $emails
     *
     * @return self
     */
    public function setEmails(array $emails): self
    {
        $this->emails = $emails;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param string $description
     *
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
