<?php

namespace Newageerp\SfReactTemplates\AppTemplates\List\DataView;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListDataSource;
use Symfony\Contracts\EventDispatcher\Event;

class DataViewListDataSourceEvent extends Event
{
    public const NAME = 'AppTemplatesListDataViewListDataSourceEvent';

    protected string $schema = '';
    protected string $type = '';

    protected ?ListDataSource $listDataSource = null;

    public function __construct(
        string $schema,
        string $type,
        ListDataSource $listDataSource,
    ) {
        $this->schema = $schema;
        $this->type = $type;
        $this->listDataSource = $listDataSource;
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

    /**
     * Get the value of listDataSource
     *
     * @return ?ListDataSource
     */
    public function getListDataSource(): ?ListDataSource
    {
        return $this->listDataSource;
    }

    /**
     * Set the value of listDataSource
     *
     * @param ?ListDataSource $listDataSource
     *
     * @return self
     */
    public function setListDataSource(?ListDataSource $listDataSource): self
    {
        $this->listDataSource = $listDataSource;

        return $this;
    }
}
