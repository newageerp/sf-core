import React, { Fragment } from 'react'
import { MenuItem, MenuItemProps } from '@newageerp/v3.bundles.modal-bundle'
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { filterScopes } from '../utils';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { useTemplatesCore } from '@newageerp/v3.templates.templates-core';

declare type Props = {
    elementId: number,
    
    action: string,
    
    scopes?: string[],
} & MenuItemProps;

export default function MenuItemWithAction(props: Props) {
    const {data: tData} = useTemplatesLoader();

    const {userState} = useTemplatesCore()

    const [doReq, doReqParams] = OpenApi.useURequest(props.action);

    const isShow = filterScopes(
        tData.element,
        userState,
        props.scopes
    );


    const onClick = () => {
        doReq({id: props.elementId})
    }

    return (
        <MenuItem {...props} onClick={onClick} isDisabled={!isShow} />
    )
}
