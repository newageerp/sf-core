<?php

namespace Newageerp\SfPublic;

use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(path="/app/public")
 */
class SfPublicController extends AbstractController
{
    /**
     * @Route(path="/version")
     */
    public function version()
    {
        $content = '0';
        $projectDir = $this->getParameter('kernel.project_dir');
        if (file_exists($projectDir.'/version.txt')) {
            $content = file_get_contents($projectDir.'/version.txt');
        }

        $textResponse = new Response($content , 200);
        $textResponse->headers->set('Content-Type', 'text/plain');
        return $textResponse;
    }
}
