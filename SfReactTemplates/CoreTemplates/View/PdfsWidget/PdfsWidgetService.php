<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View\PdfsWidget;

use Newageerp\SfControlpanel\Console\PdfsUtilsV3;

class PdfsWidgetService
{
    protected PdfsUtilsV3 $pdfsUtilsV3;

    public function __construct(PdfsUtilsV3 $pdfsUtilsV3)
    {
        $this->pdfsUtilsV3 = $pdfsUtilsV3;
    }

    public function getPdfContainerForSchema(string $schema, int $id)
    {
        $items = $this->pdfsUtilsV3->getPdfItemsForSchema($schema);

        $pdfContainer = new PdfContainer();
        foreach ($items as $item) {
            $pdfItem = new PdfItem(
                $id,
                $schema,
                $item['template'],
                $item['title']
            );
            $pdfContainer->getItems()->addTemplate($pdfItem);
        }
        return $pdfContainer;
    }
}
