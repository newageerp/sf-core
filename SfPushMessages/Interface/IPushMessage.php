<?php
namespace Newageerp\SfPushMessages\Interface;

interface IPushMessage {
    public function getNotificationTitle() : string;

    public function getNotificationBody() : string;

    public function getNotificationData() : array;
}