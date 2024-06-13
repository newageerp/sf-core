<?php

namespace Newageerp\SfLogs;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfFiles\Service\FilesHelperService;

/**
 * @Route(path="/app/nae-core/sf-logs")
 */
class SfLogsController extends OaBaseController
{

    /**
     * @Route ("/list", methods={"GET"})
     */
    public function list(): Response
    {
        $list = FilesHelperService::scanDirFiles('/var/www/symfony/var/log');

        $html = '';

        foreach ($list as $el) {
            $html .= '<p>' . $el . ' ('.ceil(filesize('/var/www/symfony/var/log/'.$el)/1024).' KB)  <a href="/app/nae-core/sf-logs/view/' . $el . '">view</a> | <a href="/app/nae-core/sf-logs/clear/' . $el . '">clear</a></p>';
        }

        return new Response($html, 200, ['Content-Type' => 'text/html']);
    }


    /**
     * @Route ("/view/:f", methods={"GET"})
     */
    public function view(Request $request): Response
    {
        $fileContent = file_get_contents('/var/www/symfony/var/log/' . str_replace('.log', '', $request->get('f')) . '.log');

        return new Response($fileContent);
    }

    /**
     * @Route ("/clear/:f", methods={"GET"})
     */
    public function clear(Request $request): Response
    {
        file_put_contents('/var/www/symfony/var/log/' . str_replace('.log', '', $request->get('f')) . '.log', '');

        return new Response('OK');
    }
}
