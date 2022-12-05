import React, { Fragment } from 'react'
import { MenuItem, MenuItemProps } from '@newageerp/v3.bundles.modal-bundle'
import { useRecoilValue } from 'recoil';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { filterScopes } from '../utils';
import { useTemplateLoader } from '../templates/TemplateLoader';

declare type Props = {
    link: string,
    
    scopes?: string[],
} & MenuItemProps;

export default function MenuItemWithLink(props: Props) {
    const {data: tData} = useTemplateLoader();

    const userState = useRecoilValue(OpenApi.naeUserState);
    
    const isShow = filterScopes(
        tData.element,
        userState,
        props.scopes
    );


    const onClick = () => {
        window.open(props.link, '_blank')
    }

    return (
        <MenuItem {...props} onClick={onClick} isDisabled={!isShow} />
    )
}
