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
     * @Route ("/list", methods={"GET"})
     */
    public function list()
    {
        $list = json_decode(file_get_contents('http://artifactory.767.lt:8000/index.php?action=list'), true);

        return $this->render('bin_list.html.twig', ['list' => $list]);
    }
}
