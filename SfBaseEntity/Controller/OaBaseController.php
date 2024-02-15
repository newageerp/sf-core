<?php

namespace Newageerp\SfBaseEntity\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfBaseEntity\Interface\IUser;
use Newageerp\SfBaseEntity\Object\BaseUser;
use Newageerp\SfEntity\SfEntityService;
use Newageerp\SfSocket\Service\SocketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OaBaseController extends AbstractController
{
    protected ObjectRepository $userRepository;

    protected EntityManagerInterface $em;

    protected EventDispatcherInterface $eventDispatcher;

    protected SocketService $socketService;

    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, SocketService $socketService)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->em = $em;
        if (class_exists('App\Entity\User')) {
            $this->userRepository = $em->getRepository('App\\Entity\\User');
        }
        $this->socketService = $socketService;
    }

    /**
     * @param Request $request
     * @return Request
     */
    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

    /**
     * @param string $schema
     * @return string
     */
    protected function convertSchemaToEntity(string $schema): string
    {
        $entityClass = implode('', array_map('ucfirst', explode("-", $schema)));

        return SfEntityService::entityByName($entityClass);
    }

    /**
     * @return Response
     */
    protected function noAuthError(): Response
    {
        return $this->returnError(0, 'Wrong token');
    }

    /**
     * @param int $code
     * @param string $description
     * @param array|null $extraData
     * @return Response
     */
    protected function returnError(int $code, string $description, ?array $extraData = []): Response
    {
        return $this->signErrorResponse(
            array_merge(
                [
                    'isError' => true,
                    'error' => ['code' => $code, 'description' => $description],
                ],
                $extraData
            )
        );
    }

    /**
     * @param $output
     * @param Request|null $request
     * @return Response
     */
    protected function signErrorResponse($output, Request $request = null): Response
    {
        $response = $this->json($output);
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);

        if ($request && $request->headers->get('origin')) {
            $origin = $request->headers->get('origin');
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        } else {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return IUser|null
     */
    public function findUser(Request $request): ?IUser
    {
        $className = 'App\Entity\User';
        if (!class_exists($className)) {
            $user = new BaseUser();
            $user->setId(1);
            return $user;
        }
        $token = $request->get('token') ? $request->get('token') : $request->headers->get('Authorization');
        if ($token) {
            $url = AuthService::getInstance()->getBackendUrl() . '/api/check';

            $ppData = [
                'data' => [
                    'token' => $token
                ]
            ];

            $ch = curl_init($url);
            $payload = json_encode($ppData);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if ($result && isset($result['id']) && isset($result['iat']) && ($result['iat'] + 86400) > time()) {
                if (method_exists($className, 'getAuthSourceId')) {
                    return $this->userRepository->findOneBy(['authSourceId' => $result['id']]);
                }
                return $this->userRepository->find($result['id']);
            }
        }
        return null;
    }

    public function sendSocketPool()
    {
        $this->socketService->sendPool();
    }

    /**
     * Get the value of eventDispatcher
     *
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * Set the value of eventDispatcher
     *
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return self
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): self
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }
}
