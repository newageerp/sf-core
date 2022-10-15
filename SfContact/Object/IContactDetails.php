<?php

namespace Newageerp\SfContact\Object;

interface IContactDetails {

    public function getFirstName(): string;

    public function setFirstName(string $firstName): self;

    public function getLastName(): string;

    public function setLastName(string $lastName): self;

    public function getPosition(): string;

    public function setPosition(string $position): self;

    public function getPhones(): array;

    public function setPhones(array $phones): self;

    public function getEmails(): array;

    public function setEmails(array $emails): self;

    public function getDescription(): string;

    public function setDescription(string $description): self;

    public function getFullName(): string;
}