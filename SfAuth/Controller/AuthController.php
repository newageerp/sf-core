<?php

namespace Newageerp\SfAuth\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Newageerp\SfAuth\Service\AuthService;
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
        if (class_exists($this->className)) {
            $this->userRepository = $entityManager->getRepository($this->className);
        }
    }

    /**
     * @Route(path="/register-user")
     */
    public function registerUser(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $user = $request->get('user');
        $auth = $request->get('auth');

        $className = $this->className;

        $userOrm = new $className();
        if (method_exists($userOrm, 'setId')) {
            $userOrm->setId($user['id']);
        }
        if (method_exists($userOrm, 'setFirstName')) {
            $userOrm->setFirstName($user['firstName']);
        }
        if (method_exists($userOrm, 'setLastName')) {
            $userOrm->setLastName($user['lastName']);
        }
        if (method_exists($userOrm, 'setPhone')) {
            $userOrm->setPhone($user['phone']);
        }
        if (method_exists($userOrm, 'setEmail')) {
            $userOrm->setEmail($user['email']);
        }
        if (method_exists($userOrm, 'setLogin')) {
            $userOrm->setLogin($auth['username']);
        }
        if (method_exists($userOrm, 'setAuthSourceId')) {
            $userOrm->setAuthSourceId($user['id']);
        }
        $this->getEm()->persist($userOrm);
        $this->getEm()->flush();

        return $this->json(['success' => 1]);
    }

    /**
     * @Route(path="/sync-user")
     */
    public function syncUser(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $user = $request->get('user');

        $className = $this->className;
        $repo = $this->getEm()->getRepository($this->className);

        if (method_exists($className, 'getAuthSourceId')) {
            $userOrm = $repo->findOneBy(['authSourceId' => $user['id']]);
        } else {
            $userOrm = $repo->find($user['id']);
        }

        if (!$userOrm) {
            $userOrm = new $className();
        }
        if (method_exists($userOrm, 'setId')) {
            $userOrm->setId($user['id']);
        }
        if (method_exists($userOrm, 'setFirstName')) {
            $userOrm->setFirstName($user['firstName']);
        }
        if (method_exists($userOrm, 'setLastName')) {
            $userOrm->setLastName($user['lastName']);
        }
        if (method_exists($userOrm, 'setPhone')) {
            $userOrm->setPhone($user['phone']);
        }
        if (method_exists($userOrm, 'setEmail')) {
            $userOrm->setEmail($user['email']);
        }
        if (method_exists($userOrm, 'setAuthSourceId')) {
            $userOrm->setAuthSourceId($user['id']);
        }
        if (method_exists($userOrm, 'setPermissionGroup')) {
            $userOrm->setPermissionGroup('none');
        }
        $this->getEm()->persist($userOrm);
        $this->getEm()->flush();

        return $this->json(['success' => 1]);
    }

    /**
     * @Route(path="/password-update")
     */
    public function generateUpdatePasswordLink(Request $request, AuthService $authService)
    {
        $link = '/login/update';

        $token = $request->get('token') ? $request->get('token') : $request->headers->get('Authorization');
        if ($token) {
            $url = $authService->getBackendUrl() . '/api/generate-update-token';

            $ppData = [
                'data' => [
                    'token' => $token,
                    'forUserId' => (int)$request->get('id'),
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
        if (!class_exists($this->className)) {
            return $this->json([
                'id' => 1,
            ]);
        }
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
