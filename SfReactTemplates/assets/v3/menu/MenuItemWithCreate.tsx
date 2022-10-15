import React, { Fragment } from 'react'
import { MenuItem, MenuItemProps } from '@newageerp/v3.modal.menu-item'
import { UI } from '@newageerp/nae-react-ui';
import { useRecoilValue } from 'recoil';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { filterScopes } from '../utils';
import { useTemplateLoader } from '../templates/TemplateLoader';

declare type Props = {
    elementId: number,
    targetSchema: string,
    sourceSchema: string,
    forcePopup: boolean,
    createOptions?: any,
    scopes?: string[],
} & MenuItemProps;

export default function MenuItemWithCreate(props: Props) {
    const {data: tData} = useTemplateLoader();

    const userState = useRecoilValue(OpenApi.naeUserState);

    const { isPopup } = UI.Popup.useNaePopup();
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
