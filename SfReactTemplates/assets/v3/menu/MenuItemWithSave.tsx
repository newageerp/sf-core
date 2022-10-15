import React from 'react'
import { MenuItem, MenuItemProps } from '@newageerp/v3.modal.menu-item'
import { OpenApi } from "@newageerp/nae-react-auth-wrapper";

declare type Props = {
    schema: string,
    saveData: any,
    elementId: number,
} & MenuItemProps;

export default function MenuItemWithSave(props: Props) {
    const [saveData] = OpenApi.useUSave(props.schema);

    const onClick = () => {
        saveData(props.saveData, props.elementId);
    };

    return (
        <MenuItem {...props} onClick={onClick} />
    )
}
