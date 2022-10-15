<?php

namespace Newageerp\SfTasks\Interface;

use Newageerp\SfBaseEntity\Interface\IUser;

interface ITask
{
    /**
     * @return int
     */
    public function getRepeatPeriod(): int;

    /**
     * @param int $repeatPeriod
     */
    public function setRepeatPeriod(int $repeatPeriod): void;

    /**
     * @return int
     */
    public function getLongTerm(): int;

    /**
     * @param int $longTerm
     */
    public function setLongTerm(int $longTerm): void;

    /**
     * @return ITaskCategory|null
     */
    public function getTaskCategory(): ?ITaskCategory;

    /**
     * @param ITaskCategory|null $taskCategory
     */
    public function setTaskCategory(?ITaskCategory $taskCategory): void;

    /**
     * @return ITask|null
     */
    public function getRelatedTask(): ?ITask;

    /**
     * @param ITask|null $relatedTask
     */
    public function setRelatedTask(?ITask $relatedTask): void;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @return bool
     */
    public function isCompleted(): bool;

    /**
     * @param bool $completed
     */
    public function setCompleted(bool $completed): void;

    /**
     * @return \DateTime|null
     */
    public function getDueDate(): ?\DateTime;

    /**
     * @param \DateTime|null $dueDate
     */
    public function setDueDate(?\DateTime $dueDate): void;

    /**
     * @return IUser|null
     */
    public function getDoer(): ?IUser;

    /**
     * @param IUser|null $doer
     */
    public function setDoer(?IUser $doer): void;

    /**
     * @return IUser|null
     */
    public function getCreator(): ?IUser;

    /**
     * @param IUser|null $creator
     */
    public function setCreator(?IUser $creator): void;

    /**
     * @return int
     */
    public function getParentId(): int;

    /**
     * @param int $parentId
     */
    public function setParentId(int $parentId): void;

    /**
     * @return string
     */
    public function getParentSchema(): string;

    /**
     * @param string $parentSchema
     */
    public function setParentSchema(string $parentSchema): void;

    /**
     * @return array|null
     */
    public function getFiles(): ?array;

    /**
     * @param array|null $files
     */
    public function setFiles(?array $files): void;
}