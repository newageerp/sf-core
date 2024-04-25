<?php

namespace Newageerp\SfReactTemplates\AppTemplates\List\DataView;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListDataSource;
use Symfony\Contracts\EventDispatcher\Event;

class DataViewListTableEvent extends Event
{
    public const NAME = 'AppTemplatesListDataViewListTableEvent';

    protected string $schema = '';
    protected string $type = '';

    protected array $headerColumns = [];
    protected array $bodyColumns = [];

    public function __construct(
        string $schema,
        string $type,
    ) {
        $this->schema = $schema;
        $this->type = $type;
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
