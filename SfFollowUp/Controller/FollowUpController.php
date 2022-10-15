<?php

namespace Newageerp\SfFollowUp\Controller;

use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfFollowUp\Object\BaseFollowUp;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/app/nae-core/follow-up")
 */
class FollowUpController extends OaBaseController
{
    protected string $className = 'App\\Entity\\FollowUp';

    /**
     * @Route(path="/getElement", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function getElement(Request $request): Response
    {
        $request = $this->transformJsonBody($request);

        try {
            if (!($user = $this->findUser($request))) {
                throw new \Exception('Invalid user');
            }

            $parentSchema = $request->get('parentSchema');
            $parentId = $request->get('parentId');

            $repo = $this->getEm()->getRepository($this->className);

            /**
             * @var BaseFollowUp $element
             */
            $element = $repo->findOneBy([
                'parentId' => $parentId,
                'parentSchema' => $parentSchema,
                'creator' => $user
            ]);
            if (!$element) {
                return $this->json(['success' => 0]);
            }

            return $this->json(['success' => 1, 'data' => [
                'onDate' => $element->getOnDate() ? $element->getOnDate()->format('Y-m-d') : '',
                'comment' => $element->getComment()
            ]]);
        } catch (\Exception $e) {
            return $this->json(['success' => 0, 'e' => $e->getMessage(), 'f' => $e->getFile(), 'l' => $e->getLine()]);
        }
    }

    /**
     * @Route(path="/saveElement", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function saveElement(Request $request): Response
    {
        $request = $this->transformJsonBody($request);

        $className = $this->className;
        try {
            if (!($user = $this->findUser($request))) {
                throw new \Exception('Invalid user');
            }

            $parentSchema = $request->get('parentSchema');
            $parentId = $request->get('parentId');
            $onDate = $request->get('onDate');
            $comment = $request->get('comment');

            $repo = $this->getEm()->getRepository($this->className);

            /**
             * @var BaseFollowUp $element
             */
            $element = $repo->findOneBy([
                'parentId' => $parentId,
                'parentSchema' => $parentSchema,
                'creator' => $user
            ]);
            if (!$element) {
                $element = new $className();
                $element->setCreator($user);
                $element->setParentSchema($parentSchema);
                $element->setParentId($parentId);
                $this->getEm()->persist($element);
            }
            $element->setOnDate(new \DateTime($onDate));
            $element->setComment($comment);

            $this->getEm()->flush();

            $this->sendSocketPool();

            return $this->json(['success' => 1]);
        } catch (\Exception $e) {
            return $this->json(['success' => 0, 'e' => $e->getMessage(), 'f' => $e->getFile(), 'l' => $e->getLine()]);
        }
    }

    /**
     * @Route(path="/removeElement", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function removeElement(Request $request): Response
    {
        $request = $this->transformJsonBody($request);

        try {
            if (!($user = $this->findUser($request))) {
                throw new \Exception('Invalid user');
            }

            $parentSchema = $request->get('parentSchema');
            $parentId = $request->get('parentId');

            $repo = $this->getEm()->getRepository($this->className);

            /**
             * @var BaseFollowUp $element
             */
            $element = $repo->findOneBy([
                'parentId' => $parentId,
                'parentSchema' => $parentSchema,
                'creator' => $user
            ]);
            if (!$element) {
                return $this->json(['success' => 0]);
            }
            $this->getEm()->remove($element);
            $this->getEm()->flush();

            $this->sendSocketPool();

            return $this->json(['success' => 1]);
        } catch (\Exception $e) {
            return $this->json(['success' => 0, 'e' => $e->getMessage(), 'f' => $e->getFile(), 'l' => $e->getLine()]);
        }
    }
}