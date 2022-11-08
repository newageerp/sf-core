<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class InLocalConfigSyncUsersPermissionsConsole extends Command
{
    protected static $defaultName = 'nae:localconfig:InLocalConfigSyncUsersPermissions';

    protected EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configPath = Utils::customFolderPath('config') . '/NaeSPermissions.tsx';

        $fileContent = "import React, { Fragment } from 'react';
import { useRecoilValue } from 'recoil';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';

export const checkUserPermission = (userState: any, permission: string) => {
    return userState.scopes.indexOf(permission) >= 0;
};

interface ICheckUserPermissionComponent {
    children: any,
    permissions?: ENaeSPermissions[],
    permissionsStr?: string,
}
export const CheckUserPermissionComponent = (props: ICheckUserPermissionComponent) => {
    const userState = useRecoilValue(OpenApi.naeUserState);

    const permissions = props.permissions?props.permissions:(props.permissionsStr?JSON.parse(props.permissionsStr):undefined);

    if (!permissions) {
      return <Fragment />
    }

    let isOk = false;
    permissions.forEach((permission: ENaeSPermissions) => {
        if (checkUserPermission(userState, permission)) {
            isOk = true;
        }
    });
    if (!isOk) {
        return <Fragment />
    }
    return props.children;
}
";

        $permissionsData = LocalConfigUtils::getCpConfigFileData('user-permissions');

        $permissions[] = [
            'key' => 'default',
            'slug' => 'default',
            'title' => 'default',
        ];
        foreach ($permissionsData as $permission) {
            $permissions[] = [
                'key' => LocalConfigUtils::transformKeyToCamelCase($permission['config']['slug']),
                'slug' => $permission['config']['slug'],
                'title' => $permission['config']['title'],
            ];
        }

        $enumText = '';
        $enumText .= 'export enum ENaeSPermissions {';

        foreach ($permissions as $el) {
            $enumText .= '
' . ucfirst($el['key']) . ' = "' . $el['slug'] . '",';

            $fileContent .= '
export const check' . ucfirst($el['key']) . ' = (userState: any) => {
    return userState.scopes.indexOf("' . $el['slug'] . '") >= 0;
};';
        }

        $enumText .= '}';

        $fileContent .= $enumText;

        $fileContent .= '
export const NaeSPermissions = ' . json_encode($permissions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        file_put_contents(
            $configPath,
            $fileContent
        );

        return Command::SUCCESS;
    }
}
