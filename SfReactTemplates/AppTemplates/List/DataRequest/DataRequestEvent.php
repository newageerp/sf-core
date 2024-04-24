<?php

namespace Newageerp\SfReactTemplates\AppTemplates\List\DataRequest;

use Symfony\Contracts\EventDispatcher\Event;

class DataRequestEvent extends Event
{
    public const NAME = 'AppTemplatesListDataRequestEvent';

    protected string $schema = '';
    protected string $type = '';

    protected int $requestPage = 1;
    protected int $requestPageSize = 20;
    protected array $requestFieldsToReturn = [];
    protected array $requestFilters = [];
    protected array $requestExtraData = [];
    protected array $requestSort = [];
    protected array $requestMetrics = [];

    protected array $responseMetrics = [];
    protected int $responseRecords = 0;
    protected array $responseData = [];
    protected string $responseCacheToken = '';

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

    /**
     * Get the value of requestPage
     *
     * @return int
     */
    public function getRequestPage(): int
    {
        return $this->requestPage;
    }

    /**
     * Set the value of requestPage
     *
     * @param int $requestPage
     *
     * @return self
     */
    public function setRequestPage(int $requestPage): self
    {
        $this->requestPage = $requestPage;

        return $this;
    }

    /**
     * Get the value of requestPageSize
     *
     * @return int
     */
    public function getRequestPageSize(): int
    {
        return $this->requestPageSize;
    }

    /**
     * Set the value of requestPageSize
     *
     * @param int $requestPageSize
     *
     * @return self
     */
    public function setRequestPageSize(int $requestPageSize): self
    {
        $this->requestPageSize = $requestPageSize;

        return $this;
    }

    /**
     * Get the value of requestFilters
     *
     * @return array
     */
    public function getRequestFilters(): array
    {
        return $this->requestFilters;
    }

    /**
     * Set the value of requestFilters
     *
     * @param array $requestFilters
     *
     * @return self
     */
    public function setRequestFilters(array $requestFilters): self
    {
        $this->requestFilters = $requestFilters;

        return $this;
    }

    /**
     * Get the value of requestExtraData
     *
     * @return array
     */
    public function getRequestExtraData(): array
    {
        return $this->requestExtraData;
    }

    /**
     * Set the value of requestExtraData
     *
     * @param array $requestExtraData
     *
     * @return self
     */
    public function setRequestExtraData(array $requestExtraData): self
    {
        $this->requestExtraData = $requestExtraData;

        return $this;
    }

    /**
     * Get the value of requestSort
     *
     * @return array
     */
    public function getRequestSort(): array
    {
        return $this->requestSort;
    }

    /**
     * Set the value of requestSort
     *
     * @param array $requestSort
     *
     * @return self
     */
    public function setRequestSort(array $requestSort): self
    {
        $this->requestSort = $requestSort;

        return $this;
    }

    /**
     * Get the value of requestMetrics
     *
     * @return array
     */
    public function getRequestMetrics(): array
    {
        return $this->requestMetrics;
    }

    /**
     * Set the value of requestMetrics
     *
     * @param array $requestMetrics
     *
     * @return self
     */
    public function setRequestMetrics(array $requestMetrics): self
    {
        $this->requestMetrics = $requestMetrics;

        return $this;
    }

    /**
     * Get the value of requestFieldsToReturn
     *
     * @return array
     */
    public function getRequestFieldsToReturn(): array
    {
        return $this->requestFieldsToReturn;
    }

    /**
     * Set the value of requestFieldsToReturn
     *
     * @param array $requestFieldsToReturn
     *
     * @return self
     */
    public function setRequestFieldsToReturn(array $requestFieldsToReturn): self
    {
        $this->requestFieldsToReturn = $requestFieldsToReturn;

        return $this;
    }

    /**
     * Get the value of responseMetrics
     *
     * @return array
     */
    public function getResponseMetrics(): array
    {
        return $this->responseMetrics;
    }

    /**
     * Set the value of responseMetrics
     *
     * @param array $responseMetrics
     *
     * @return self
     */
    public function setResponseMetrics(array $responseMetrics): self
    {
        $this->responseMetrics = $responseMetrics;

        return $this;
    }

    /**
     * Get the value of responseRecords
     *
     * @return int
     */
    public function getResponseRecords(): int
    {
        return $this->responseRecords;
    }

    /**
     * Set the value of responseRecords
     *
     * @param int $responseRecords
     *
     * @return self
     */
    public function setResponseRecords(int $responseRecords): self
    {
        $this->responseRecords = $responseRecords;

        return $this;
    }

    /**
     * Get the value of responseData
     *
     * @return array
     */
    public function getResponseData(): array
    {
        return $this->responseData;
    }

    /**
     * Set the value of responseData
     *
     * @param array $responseData
     *
     * @return self
     */
    public function setResponseData(array $responseData): self
    {
        $this->responseData = $responseData;

        return $this;
    }


    /**
     * Get the value of responseCacheToken
     *
     * @return string
     */
    public function getResponseCacheToken(): string
    {
        return $this->responseCacheToken;
    }

    /**
     * Set the value of responseCacheToken
     *
     * @param string $responseCacheToken
     *
     * @return self
     */
    public function setResponseCacheToken(string $responseCacheToken): self
    {
        $this->responseCacheToken = $responseCacheToken;

        return $this;
    }
}
