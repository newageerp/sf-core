<?php

namespace Newageerp\SfCurrency\Controller;

use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfCurrency\Service\CurrencyService;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(path="/app/nae-core/currency")
 */
class CurrencyController extends OaBaseController
{
    /**
     * @Route(path="/getRateFor", methods={"POST"})
     */
    public function getRateFor(Request $request, CurrencyService $currencyService)
    {
        $request = $this->transformJsonBody($request);
        $date = $request->get('date');
        $currency = $request->get('currency');

        return $this->json(['data' => $currencyService->getRateFor($currency, $date)]);
    }

    /**
     * @Route(path="/getRateForCurrencyId", methods={"POST"})
     */
    public function getRateForCurrencyId(Request $request, CurrencyService $currencyService)
    {
        $request = $this->transformJsonBody($request);
        $date = $request->get('date');
        $repo = $this->getEm()->getRepository('App\Entity\Currency');
        $currencyId = $request->get('currencyId');
        $currencyOrm = $repo->find($currencyId);

        return $this->json(['data' => $currencyService->getRateFor($currencyOrm->getCurrency(), $date)]);
    }
}