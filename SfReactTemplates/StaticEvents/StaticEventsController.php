<?php

namespace Newageerp\SfReactTemplates\StaticEvents;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;

/**
 * @Route(path="/app/nae-core/static-events/")
 */
class StaticEventsController extends OaBaseController
{

    /**
     * @Route(path="add", methods={"POST"})
     */
    public function getTemplates(Request $request): Response
    {
        $request = $this->transformJsonBody($request);

        $data = $request->get('data');
        $groupName = $request->get('groupName');


        $staticEvents = LocalConfigUtilsV3::getCpConfigFileData('static-events');
        $staticEvents[$groupName] = $data;

        $file = LocalConfigUtilsV3::getNaeSfsCpStoragePath() . '/static-events.json';
        file_put_contents(
            $file,
            json_encode($staticEvents, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return $this->json(['success' => 1]);
    }
}
