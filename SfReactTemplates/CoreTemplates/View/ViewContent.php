<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View;

use Newageerp\SfPermissions\Service\EntityPermissionService;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ViewContent extends Template
{
    protected string $schema = '';

    protected string $type = '';

    protected string $id = '';

    protected ?object $entity = null;

    protected Placeholder $rightContent;
    protected Placeholder $bottomContent;

    protected Placeholder $afterTitleBlockContent;
    protected Placeholder $elementToolbarAfterFieldsContent;
    protected Placeholder $elementToolbarLine2BeforeContent;

    protected Placeholder $elementToolbarMoreMenuContent;

    protected ViewFormContent $formContent;

    public function __construct(string $schema, string $type, string $id, ?object $entity)
    {
        $this->schema = $schema;
        $this->type = $type;
        $this->id = $id;
        $this->entity = $entity;

        $this->rightContent = new Placeholder();
        $this->bottomContent = new Placeholder();

        $this->afterTitleBlockContent = new Placeholder();
        $this->elementToolbarAfterFieldsContent = new Placeholder();
        $this->elementToolbarLine2BeforeContent = new Placeholder();
        $this->elementToolbarMoreMenuContent = new Placeholder();

        $this->formContent = new ViewFormContent($schema, $type);
    }

    public function getProps(): array
    {
        return [
            'formContent' => [$this->getFormContent()->toArray()],
            'editable' => EntityPermissionService::checkIsEditable($this->entity),
            'removable' => EntityPermissionService::checkIsRemovable($this->entity),

            'rightContent' => $this->getRightContent()->toArray(),
            'bottomContent' => $this->getBottomContent()->toArray(),

            'afterTitleBlockContent' => $this->getAfterTitleBlockContent()->toArray(),
            'elementToolbarAfterFieldsContent' => $this->getElementToolbarAfterFieldsContent()->toArray(),
            'elementToolbarLine2BeforeContent' => $this->getElementToolbarLine2BeforeContent()->toArray(),

            'elementToolbarMoreMenuContent' => $this->getElementToolbarMoreMenuContent()->toArray(),

            'schema' => $this->getSchema(),
            'type' => $this->getType(),
            'id' => $this->getId(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'view.content';
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
     * Get the value of id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param string $id
     *
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of rightContent
     *
     * @return Placeholder
     */
    public function getRightContent(): Placeholder
    {
        return $this->rightContent;
    }

    /**
     * Set the value of rightContent
     *
     * @param Placeholder $rightContent
     *
     * @return self
     */
    public function setRightContent(Placeholder $rightContent): self
    {
        $this->rightContent = $rightContent;

        return $this;
    }

    /**
     * Get the value of afterTitleBlockContent
     *
     * @return Placeholder
     */
    public function getAfterTitleBlockContent(): Placeholder
    {
        return $this->afterTitleBlockContent;
    }

    /**
     * Set the value of afterTitleBlockContent
     *
     * @param Placeholder $afterTitleBlockContent
     *
     * @return self
     */
    public function setAfterTitleBlockContent(Placeholder $afterTitleBlockContent): self
    {
        $this->afterTitleBlockContent = $afterTitleBlockContent;

        return $this;
    }

    /**
     * Get the value of elementToolbarAfterFieldsContent
     *
     * @return Placeholder
     */
    public function getElementToolbarAfterFieldsContent(): Placeholder
    {
        return $this->elementToolbarAfterFieldsContent;
    }

    /**
     * Set the value of elementToolbarAfterFieldsContent
     *
     * @param Placeholder $elementToolbarAfterFieldsContent
     *
     * @return self
     */
    public function setElementToolbarAfterFieldsContent(Placeholder $elementToolbarAfterFieldsContent): self
    {
        $this->elementToolbarAfterFieldsContent = $elementToolbarAfterFieldsContent;

        return $this;
    }

    /**
     * Get the value of formContent
     *
     * @return ViewFormContent
     */
    public function getFormContent(): ViewFormContent
    {
        return $this->formContent;
    }

    /**
     * Set the value of formContent
     *
     * @param ViewFormContent $formContent
     *
     * @return self
     */
    public function setFormContent(ViewFormContent $formContent): self
    {
        $this->formContent = $formContent;

        return $this;
    }


    /**
     * Get the value of elementToolbarLine2BeforeContent
     *
     * @return Placeholder
     */
    public function getElementToolbarLine2BeforeContent(): Placeholder
    {
        return $this->elementToolbarLine2BeforeContent;
    }

    /**
     * Set the value of elementToolbarLine2BeforeContent
     *
     * @param Placeholder $elementToolbarLine2BeforeContent
     *
     * @return self
     */
    public function setElementToolbarLine2BeforeContent(Placeholder $elementToolbarLine2BeforeContent): self
    {
        $this->elementToolbarLine2BeforeContent = $elementToolbarLine2BeforeContent;

        return $this;
    }

    /**
     * Get the value of elementToolbarMoreMenuContent
     *
     * @return Placeholder
     */
    public function getElementToolbarMoreMenuContent(): Placeholder
    {
        return $this->elementToolbarMoreMenuContent;
    }

    /**
     * Set the value of elementToolbarMoreMenuContent
     *
     * @param Placeholder $elementToolbarMoreMenuContent
     *
     * @return self
     */
    public function setElementToolbarMoreMenuContent(Placeholder $elementToolbarMoreMenuContent): self
    {
        $this->elementToolbarMoreMenuContent = $elementToolbarMoreMenuContent;

        return $this;
    }

    /**
     * Get the value of bottomContent
     *
     * @return Placeholder
     */
    public function getBottomContent(): Placeholder
    {
        return $this->bottomContent;
    }

    /**
     * Set the value of bottomContent
     *
     * @param Placeholder $bottomContent
     *
     * @return self
     */
    public function setBottomContent(Placeholder $bottomContent): self
    {
        $this->bottomContent = $bottomContent;

        return $this;
    }
}
