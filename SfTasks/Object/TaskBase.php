<?php
namespace Newageerp\SfTasks\Object;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Newageerp\SfBaseEntity\Interface\IUser;
use Newageerp\SfTasks\Interface\ITask;
use Newageerp\SfTasks\Interface\ITaskCategory;

class TaskBase implements ITask
{
    /**
     * @ORM\Column(type="integer")
     */
    protected int $repeatPeriod = 0;

    /**
     * @OA\Property(additionalProperties={{"as":"select"}})
     * @ORM\Column(type="integer")
     */
    protected int $longTerm = 0;

    /**
     * @OA\Property(title="Kategorija")
     * @ORM\ManyToOne(targetEntity="TaskCategory")
     */
    protected ?ITaskCategory $taskCategory = null;

    /**
     * @OA\Property(title="Užduotis")
     * @ORM\ManyToOne(targetEntity="Task")
     */
    protected ?ITask $relatedTask = null;

    /**
     * @OA\Property(type="string", format="text")
     * @ORM\Column(type="text")
     */
    protected string $description = '';

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $completed = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @OA\Property(type="string", format="datetime")
     */
    protected ?\DateTime $dueDate = null;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected ?IUser $doer = null;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected ?IUser $creator = null;

    /**
     * @ORM\Column(type="integer")
     */
    protected int $parentId = 0;

    /**
     * @ORM\Column(type="string")
     */
    protected string $parentSchema = '';

    /**
     * @ORM\Column (type="json", nullable=true)
     * @OA\Property(type="array", @OA\Items(type="string"), additionalProperties={{"as":"fileMultiple"}})
     */
    protected ?array $files = [];

    /**
     * @return int
     */
    public function getRepeatPeriod(): int
    {
        return $this->repeatPeriod;
    }

    /**
     * @param int $repeatPeriod
     */
    public function setRepeatPeriod(int $repeatPeriod): void
    {
        $this->repeatPeriod = $repeatPeriod;
    }

    /**
     * @return int
     */
    public function getLongTerm(): int
    {
        return $this->longTerm;
    }

    /**
     * @param int $longTerm
     */
    public function setLongTerm(int $longTerm): void
    {
        $this->longTerm = $longTerm;
    }

    /**
     * @return ITaskCategory|null
     */
    public function getTaskCategory(): ?ITaskCategory
    {
        return $this->taskCategory;
    }

    /**
     * @param ITaskCategory|null $taskCategory
     */
    public function setTaskCategory(?ITaskCategory $taskCategory): void
    {
        $this->taskCategory = $taskCategory;
    }

    /**
     * @return ITask|null
     */
    public function getRelatedTask(): ?ITask
    {
        return $this->relatedTask;
    }

    /**
     * @param ITask|null $relatedTask
     */
    public function setRelatedTask(?ITask $relatedTask): void
    {
        $this->relatedTask = $relatedTask;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
    }

    /**
     * @return \DateTime|null
     */
    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    /**
     * @param \DateTime|null $dueDate
     */
    public function setDueDate(?\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return IUser|null
     */
    public function getDoer(): ?IUser
    {
        return $this->doer;
    }

    /**
     * @param IUser|null $doer
     */
    public function setDoer(?IUser $doer): void
    {
        $this->doer = $doer;
    }

    /**
     * @return IUser|null
     */
    public function getCreator(): ?IUser
    {
        return $this->creator;
    }

    /**
     * @param IUser|null $creator
     */
    public function setCreator(?IUser $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return string
     */
    public function getParentSchema(): string
    {
        return $this->parentSchema;
    }

    /**
     * @param string $parentSchema
     */
    public function setParentSchema(string $parentSchema): void
    {
        $this->parentSchema = $parentSchema;
    }

    /**
     * @return array|null
     */
    public function getFiles(): ?array
    {
        return $this->files;
    }

    /**
     * @param array|null $files
     */
    public function setFiles(?array $files): void
    {
        $this->files = $files;
    }
}