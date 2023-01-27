<?php

namespace Newageerp\SfReactTemplates\Event;

use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTd;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class TableTdRenderEvent extends Event
{
    public const NAME = 'sfreacttemplates.TableTdRenderEvent';

    protected TableTd $tableTd;

    protected string $schema;

    protected string $type;

    protected array $col;

    public function __construct(
        string $schema,
        string $type,
        TableTd $tableTd,
        array $col,
    ) {
        $this->schema = $schema;
        $this->type = $type;
        $this->tableTd = $tableTd;
        $this->col = $col;
    }



    /**
     * Get the value of col
     *
     * @return array
     */
    public function getCol(): array
    {
        return $this->col;
    }

    /**
     * Set the value of col
     *
     * @param array $col
     *
     * @return self
     */
    public function setCol(array $col): self
    {
        $this->col = $col;

        return $this;
    }

    /**
     * Get the value of tableTd
     *
     * @return TableTd
     */
    public function getTableTd(): TableTd
    {
        return $this->tableTd;
    }

    /**
     * Set the value of tableTd
     *
     * @param TableTd $tableTd
     *
     * @return self
     */
    public function setTableTd(TableTd $tableTd): self
    {
        $this->tableTd = $tableTd;

        return $this;
    }

    /**
     * Get the value of schema
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Set the value of schema
     *
     * @param string $schema
     *
     * @return self
     */
    public function setSchema(string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Get the value of type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
