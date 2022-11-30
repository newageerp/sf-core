import React, { Fragment } from 'react'
import { MenuItem, MenuItemProps } from '@newageerp/v3.modal.menu-item'
import { useRecoilValue } from 'recoil';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { filterScopes } from '../utils';
import { useTemplateLoader } from '../templates/TemplateLoader';

declare type Props = {
    elementId: number,
    
    action: string,
    
    scopes?: string[],
} & MenuItemProps;

export default function MenuItemWithAction(props: Props) {
    const {data: tData} = useTemplateLoader();

    const userState = useRecoilValue(OpenApi.naeUserState);
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
