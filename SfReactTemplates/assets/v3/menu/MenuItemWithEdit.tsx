import React, { Fragment } from 'react'
import { MenuItem, MenuItemProps } from '@newageerp/v3.bundles.modal-bundle'
import { filterScopes } from '../utils';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { useTemplatesCore } from '@newageerp/v3.templates.templates-core';
import { usePopup } from '@newageerp/v3.bundles.popup-bundle';

declare type Props = {
    elementId: number,
    
    schema: string,
    type: string,

    forcePopup: boolean,
    
    scopes?: string[],
} & MenuItemProps;

export default function MenuItemWithEdit(props: Props) {
    const {data: tData} = useTemplatesLoader();

    const {userState} = useTemplatesCore()

    const { isPopup } = usePopup();
    const openInPopup = isPopup || props.forcePopup;

    const isShow = filterScopes(
        tData.element,
        userState,
        props.scopes
    );


    const onClick = () => {
        const event = new CustomEvent(
            openInPopup ? 'SFSOpenEditModalWindow' : 'SFSOpenEditWindow',
            {
                detail: {
                    schema: props.schema,
                    id: props.elementId,
                    type: props.type
                }
            }
        );
        window.dispatchEvent(event);
    }

    return (
        <MenuItem {...props} onClick={onClick} isDisabled={!isShow} />
    )
}
