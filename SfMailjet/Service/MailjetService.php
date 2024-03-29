<?php

namespace Newageerp\SfMailjet\Service;

use Mailjet\Client;
use Mailjet\Resources;
use Newageerp\SfConfig\Service\ConfigService;

class MailjetService
{
    protected string $apiKeyPublic = '';
    protected string $apiKeyPrivate = '';

    protected ?Client $client = null;

    public function __construct()
    {
        $config = ConfigService::getConfig('mail');

        $this->apiKeyPublic = $config['mailjetPublicKey'];
        $this->apiKeyPrivate = $config['mailjetPrivateKey'];
    }

    /**
     * @param string $apiKeyPublic
     * @param string $apiKeyPrivate
     */
    public function init(
        string $apiKeyPublic,
        string $apiKeyPrivate
    ) {
        $this->apiKeyPublic = $apiKeyPublic;
        $this->apiKeyPrivate = $apiKeyPrivate;
    }

    public function contactCreateIfNotExist(string $email)
    {
        $contact = $this->getClient()->get(Resources::$Contact, ['id' => $email]);

        if (!$contact->success()) {
            $contact = $this->getClient()->post(Resources::$Contact, [
                'body' => [
                    'Email' => $email,
                ]
            ]);
        }
        return $contact;
    }

    public function contactGetSubscribes(string $email)
    {
        $response = $this->getClient()->get(Resources::$ContactGetcontactslists, ['id' => $email]);
        return $response->getData();
    }

    public function contactSubscribeToList(string $email, string $list, bool $unsubscribeFromOthers = false)
    {
        $contact = $this->contactCreateIfNotExist($email);
        if (!$contact || count($contact->getData()) === 0 || !is_array($contact->getData())) {
            return false;
        }

        $contactId = $contact->getData()[0]['ID'];
        $listId = $this->contactListGetByNameOrCreate($list);

        if ($contactId === 0 || $listId === 0) {
            return false;
        }

        $actions = [
            [
                'Action' => "addforce",
                'ListID' => $listId
            ]
        ];
        if ($unsubscribeFromOthers) {
            $subscribes = $this->contactGetSubscribes($email);
            foreach ($subscribes as $s) {
                if (!is_array($s)) {
                    continue;
                }
                if ($s['ListID'] !== $listId) {
                    $actions[] = [
                        'Action' => "remove",
                        'ListID' => $s['ListID']
                    ];
                }
            }
        }

        $body = [
            'ContactsLists' => $actions
        ];
        $response = $this->getClient()->post(Resources::$ContactManagecontactslists, ['id' => $contactId, 'body' => $body]);
        return $response->success();
    }

    public function contactListCreate(string $name): int
    {
        $body = [
            'Name' => $name
        ];
        $response = $this->getClient()->post(Resources::$Contactslist, ['body' => $body]);
        if ($response->success() && count($response->getData()) > 0) {
            return $response->getData()[0]['ID'];
        }
        return 0;
    }

    public function contactListGetByName(string $name): int
    {
        $response = $this->getClient()->get(Resources::$Contactslist, ['Name' => $name]);
        if ($response->success() && count($response->getData()) > 0) {
            return $response->getData()[0]['ID'];
        }
        return 0;
    }

    public function contactListGetByNameOrCreate(string $name): int
    {
        $response = $this->getClient()->get(Resources::$Contactslist, ['filters' => ['Name' => $name]]);
        if ($response->success() && count($response->getData()) > 0) {
            return $response->getData()[0]['ID'];
        }
        return $this->contactListCreate($name);
    }

    /**
     * @return string
     */
    public function getApiKeyPublic(): string
    {
        return $this->apiKeyPublic;
    }

    /**
     * @param string $apiKeyPublic
     */
    public function setApiKeyPublic(string $apiKeyPublic): void
    {
        $this->apiKeyPublic = $apiKeyPublic;
    }

    /**
     * @return string
     */
    public function getApiKeyPrivate(): string
    {
        return $this->apiKeyPrivate;
    }

    /**
     * @param string $apiKeyPrivate
     */
    public function setApiKeyPrivate(string $apiKeyPrivate): void
    {
        $this->apiKeyPrivate = $apiKeyPrivate;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        if (!$this->client) {
            $this->client = new Client($this->getApiKeyPublic(), $this->getApiKeyPrivate(), true, ['version' => 'v3']);
            $this->client->setConnectionTimeout(30);
            $this->client->setTimeout(30);
        }
        return $this->client;
    }

    /**
     * @return Client
     */
    public function getClientV31(): Client
    {
        if (!$this->client) {
            $this->client = new Client($this->getApiKeyPublic(), $this->getApiKeyPrivate(), true, ['version' => 'v3.1']);
            $this->client->setConnectionTimeout(30);
            $this->client->setTimeout(30);
        }
        return $this->client;
    }

    /**
     * @param Client|null $client
     */
    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }
}