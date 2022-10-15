<?php

namespace Newageerp\SfBookmarks\Controller;

use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/app/nae-core/bookmarks")
 */
class BookmarksController extends OaBaseController
{
    protected string $className = 'App\\Entity\\Bookmark';

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

            $element = $repo->findOneBy([
                'parentId' => $parentId,
                'parentSchema' => $parentSchema,
                'creator' => $user
            ]);
            if (!$element) {
                return $this->json(['success' => 0]);
            }

            return $this->json(['success' => 1]);
        } catch (\Exception $e) {
            return $this->json(['success' => 0, 'e' => $e->getMessage(), 'f' => $e->getFile(), 'l' => $e->getLine()]);
        }
    }

    /**
     * @Route(path="/toggleElement", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function toggleElement(Request $request): Response
    {
        $request = $this->transformJsonBody($request);

        $className = $this->className;
        try {
            if (!($user = $this->findUser($request))) {
                throw new \Exception('Invalid user');
            }

            $parentSchema = $request->get('parentSchema');
            $parentId = $request->get('parentId');

            $repo = $this->getEm()->getRepository($this->className);

            $element = $repo->findOneBy([
                'parentId' => $parentId,
                'parentSchema' => $parentSchema,
                'creator' => $user
            ]);
            if (!$element) {
                $bookmark = new $className();
                $bookmark->setCreator($user);
                $bookmark->setParentSchema($parentSchema);
                $bookmark->setParentId($parentId);
                $this->getEm()->persist($bookmark);
            } else {
                $this->getEm()->remove($element);
            }
            $this->getEm()->flush();

            return $this->json(['success' => 1]);
        } catch (\Exception $e) {
            return $this->json(['success' => 0, 'e' => $e->getMessage(), 'f' => $e->getFile(), 'l' => $e->getLine()]);
        }
    }
}