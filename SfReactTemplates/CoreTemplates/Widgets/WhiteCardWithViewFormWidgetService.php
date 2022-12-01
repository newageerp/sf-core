<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Widgets;

use  Newageerp\SfReactTemplates\CoreTemplates\View\InlineViewContentService;

class WhiteCardWithViewFormWidgetService
{
    protected InlineViewContentService $inlineViewContentService;

    public function __construct(
        InlineViewContentService $inlineViewContentService,
    ) {
        $this->inlineViewContentService = $inlineViewContentService;
    }

    public function buildWidget(
        int $elementId,
        string $viewId,
        ?string $title,
        ?string $editId,
        ?bool $isCompact,
        ?array $editScopes = null
    ) {
        [$schema, $type] = explode(":", $viewId);

        $t = $this->inlineViewContentService->loadView(
            $schema,
            $type,
            $elementId,
            $isCompact,
        );

        $w = new WhiteCardWithViewFormWidget(
            $title,
            $editId,
            $isCompact
        );
        if ($editScopes) {
            $w->setEditScopes($editScopes);
        }
        $w->getContent()->addTemplate($t);

        return $w;
    }
}
