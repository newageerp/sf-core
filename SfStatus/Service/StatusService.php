<?php

namespace Newageerp\SfStatus\Service;

use Newageerp\SfControlpanel\Console\LocalConfigUtils;

class StatusService
{
    public function getStatuses()
    {
        $statusData = LocalConfigUtils::getCpConfigFileData('statuses');

        $statuses = [];
        foreach ($statusData as $status) {
            $statuses[] = [
                'type' => $status['config']['type'],
                'status' => (int)$status['config']['status'],
                'text' => $status['config']['text'],
                'bgColor' => $status['config']['color'],
                'brightness' => (int)str_replace('b', '', $status['config']['brightness']),
                'schema' => $status['config']['entity'],
                'variant' => isset($status['config']['badgeVariant']) ? $status['config']['badgeVariant'] : 'blue',
            ];
        }

        usort($statuses, function ($pdfA, $pdfB) {
            if ($pdfA['schema'] < $pdfB['schema']) {
                return -1;
            }
            if ($pdfA['schema'] > $pdfB['schema']) {
                return 1;
            }
            if ($pdfA['status'] < $pdfB['status']) {
                return -1;
            }
            if ($pdfA['status'] > $pdfB['status']) {
                return 1;
            }
            return 0;
        });
        return $statuses;
    }

    public function getStatusesV2()
    {
        $statusData = LocalConfigUtils::getCpConfigFileData('statuses');

        $statuses = [];
        foreach ($statusData as $status) {
            $statuses[] = [
                'entity' => $status['config']['entity'],
                'key' => $status['config']['type'],
                'label' => $status['config']['text'],
                'value' => (int)$status['config']['status'],
                'color' => isset($status['config']['badgeVariant']) ? $status['config']['badgeVariant'] : 'blue',
            ];
        }

        usort($statuses, function ($pdfA, $pdfB) {
            if ($pdfA['entity'] < $pdfB['entity']) {
                return -1;
            }
            if ($pdfA['entity'] > $pdfB['entity']) {
                return 1;
            }
            if ($pdfA['value'] < $pdfB['value']) {
                return -1;
            }
            if ($pdfA['value'] > $pdfB['value']) {
                return 1;
            }
            return 0;
        });
        return $statuses;
    }
}
