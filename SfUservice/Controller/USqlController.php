<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Newageerp\SfUservice\Controller;

use Exception;
use Newageerp\SfAuth\Service\AuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route(path="/app/nae-core/u-sql")
 */
class USqlController extends UControllerBase
{

    /**
     * @Route(path="/get", methods={"POST"})
     */
    public function getList(
        Request  $request,
    ): Response {
        try {
            $request = $this->transformJsonBody($request);

            $user = $this->findUser($request);
            if (!$user) {
                throw new Exception('Invalid user');
            }
            AuthService::getInstance()->setUser($user);

            $sql = $request->get('sql');
            $countSql = $request->get('countSql');

            $page = $request->get('page', 1);
            $pageSize = $request->get('pageSize', 20);

            $limitStart = ($page - 1) * $pageSize;
            $sql .= ' LIMIT ' . $limitStart . ',' . $pageSize;

            $stmt = $this->getEm()->getConnection()->prepare($sql);
            $result = $stmt->executeQuery()->fetchAllAssociative();

            if ($countSql) {
                $stmt = $this->getEm()->getConnection()->prepare($countSql);
                $resultCount = $stmt->executeQuery()->fetchFirstColumn();
            }

            return $this->json(
                [
                    'data' => $result,
                    'records' => $resultCount,
                ]
            );
        } catch (Exception $e) {
            $response = $this->json([
                'description' => $e->getMessage(),
                'f' => $e->getFile(),
                'l' => $e->getLine()

            ]);
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        }
    }
}
