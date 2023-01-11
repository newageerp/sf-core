<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Db\RequestRecordProvider;
use Newageerp\SfReactTemplates\CoreTemplates\Db\RequestRecordProviderInner;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfUservice\Service\UService;
use Newageerp\SfReactTemplates\Template\Template;

class InlineViewContentService
{
    protected UService $uservice;

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected EventDispatcherInterface $eventDispatcher;

    protected ViewContentService $viewContentService;

    public function __construct(UService $uservice, EntitiesUtilsV3 $entitiesUtilsV3, EventDispatcherInterface $eventDispatcher, ViewContentService $viewContentService)
    {
        $this->uservice = $uservice;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->eventDispatcher = $eventDispatcher;
        $this->viewContentService = $viewContentService;
    }

    public function loadView(
        string $schema,
        string $type,
        int $id,
        ?bool $isCompact
    ) : Template
    {
        $requestRecordProvider = new RequestRecordProvider(
            $schema,
            $type,
            $id,
        );

        $formContent = new ViewFormContent(
            $schema,
            $type,
        );
        $requestRecordProviderInner = new RequestRecordProviderInner();
        $requestRecordProviderInner->getChildren()->addTemplate($formContent);

        $requestRecordProvider->getChildren()->addTemplate($requestRecordProviderInner);
        $requestRecordProvider->setShowOnEmpty(false);
        
        $this->viewContentService->fillFormContent(
            $schema,
            $type,
            $formContent,
            $isCompact
        );

        return $requestRecordProvider;
    }
}
