<?php

namespace Newageerp\SfApps\Controller;

use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/app/proxy")
 */
class ProxyController extends OaBaseController
{
    /**
     * @Route(path="/{app}/{method}", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function getElement(Request $request): Response
    {
        $payload = $request->getContent();

        $url = 'http://' . $request->get('app') . ':3000/api/' . $request->get('method');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $this->json($result);
    }
}
