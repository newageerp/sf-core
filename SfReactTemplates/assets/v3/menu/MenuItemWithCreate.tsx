import React, { Fragment } from 'react'
import { MenuItem, MenuItemProps } from '@newageerp/v3.bundles.modal-bundle'
import { filterScopes } from '../utils';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { useTemplatesCore } from '@newageerp/v3.templates.templates-core';
import { usePopup } from '@newageerp/v3.bundles.popup-bundle';

declare type Props = {
    elementId: number,
    targetSchema: string,
    targetType?: string,
    sourceSchema: string,
    forcePopup: boolean,
    createOptions?: any,
    scopes?: string[],
} & MenuItemProps;

export default function MenuItemWithCreate(props: Props) {
    const { data: tData } = useTemplatesLoader();

    const { userState } = useTemplatesCore()

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
                    schema: props.targetSchema,
                    type: props.targetType ? props.targetType : 'main',
                    id: "new",
                    options: {
                        createOptions: {
                            convert: {
                                schema: props.sourceSchema,
                                element: { id: props.elementId },
                                ...props.createOptions,
                            }
                        }
                    }
                }
            }
        );
        window.dispatchEvent(event);
    }

    return (
        <MenuItem {...props} onClick={onClick} isDisabled={!isShow} />
    )
}
