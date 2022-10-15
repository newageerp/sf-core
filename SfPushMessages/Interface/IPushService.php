<?php

namespace Newageerp\SfPushMessages\Interface;

interface IPushService
{
    public function sendMessage(IPushMessage $pushMessage, string $token);
}
