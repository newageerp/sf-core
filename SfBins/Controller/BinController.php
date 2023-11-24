<?php

namespace Newageerp\SfBins\Controller;

use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/app/nae-core/bin")
 */
class BinController extends OaBaseController
{
    /**
     * @Route ("/test", methods={"GET"})
     */
    public function test()
    {
        return $this->renderView(dirname(__DIR__) . '/templates/template.html.twig');
    }
}
