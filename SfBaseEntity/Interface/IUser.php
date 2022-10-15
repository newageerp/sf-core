<?php
namespace Newageerp\SfBaseEntity\Interface;

interface IUser
{
    public function getId(): ?int;

    public function getLogin(): string;

    public function getEmail(): string;

    public function getPassword(): string;

    public function getFullName(): string;

    public function getFirstName(): string;

    public function getLastName(): string;

    public function setPlainPassword(string $plainPassword);

    public function getPlainPassword(): string;
}