<?php

namespace Newageerp\SfReactTemplates\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfAuth\Service\AuthService;

/**
 * @Route(path="/app/nae-core/react-templates/")
 */
class ReactTemplatesController extends OaBaseController
{

    /**
     * @Route(path="get/{templateName}", methods={"POST"})
     */
    public function getTemplates(Request $request): Response
    {
        $request = $this->transformJsonBody($request);

        $user = $this->findUser($request);
        if (!$user) {
            throw new \Exception('Invalid user');
        }
        AuthService::getInstance()->setUser($user);

        $templatesData = $request->get('data');
        $templateName = $request->get('templateName');

        $placeholder = new Placeholder();

        $event = new LoadTemplateEvent($placeholder, $templateName, $templatesData);
        $this->getEventDispatcher()->dispatch($event, LoadTemplateEvent::NAME);

        return $this->json(['data' => $placeholder->toArray(), 'templatesData' => $placeholder->getTemplatesData(), 'success' => 1]);
    }
}
