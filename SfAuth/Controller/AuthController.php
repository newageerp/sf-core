<?php

namespace Newageerp\SfAuth\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Newageerp\SfSocket\Service\SocketService;

/**
 * @Route(path="/app/nae-core/auth")
 */
class AuthController extends OaBaseController
{
    protected string $className = 'App\\Entity\\User';

    /**
     * @var ObjectRepository $userRepository
     */
    protected ObjectRepository $userRepository;

    /**
     * AuthController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher, SocketService $socketService)
    {
        parent::__construct($entityManager, $eventDispatcher, $socketService);
        $this->userRepository = $entityManager->getRepository($this->className);
    }

    public function generateUpdatePasswordLink(Request $request)
    {
        $link = '/login/update';

        $token = $request->get('token') ? $request->get('token') : $request->headers->get('Authorization');
        if ($token) {
            $url = 'http://auth:3000/api/generate-update-token';

            $ppData = [
                'data' => [
                    'token' => $token,
                    'forUserId' => $request->get('id'),
                ]
            ];

            $ch = curl_init($url);
            $payload = json_encode($ppData);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);

            return $this->redirect($link . '?token=' . $result['token']);
        }
        return $this->json(['success' => 0]);
    }


    /**
     * @OA\Post (operationId="NAEauthGetProfile")
     * @Route(path="/get", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function getInfo(Request $request): Response
    {
        $request = $this->transformJsonBody($request);

        try {
            if ($user = $this->findUser($request)) {
                //                $userData = [
                //                    'id' => $user->getId(),
                //                    'email' => $user->getEmail(),
                //                    'fullName' => $user->getFullName(),
                //                ];
                return $this->json($user, 200, [], [AbstractNormalizer::IGNORED_ATTRIBUTES => ['_viewTitle']]);
            }
        } catch (\Exception $e) {
            $response = $this->json([
                'id' => -1,
                'description' => $e->getMessage(),
                'f' => $e->getFile(),
                'l' => $e->getLine()

            ]);
            //            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        }

        $response = $this->json([
            'id' => -1,
            'description' => "Wrong token",

        ]);
        //        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response;
    }

    public function randomStr(
        $length,
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ) {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new \Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
}
