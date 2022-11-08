import React from 'react'
import {
    MainButton as MainButtonTpl,
    MainButtonProps,
} from "@newageerp/v3.bundles.buttons-bundle";
import { OpenApi } from "@newageerp/nae-react-auth-wrapper";

declare type Props = {
    schema: string,
    saveData: any,
    elementId: number,
    onSaveCallback?: () => void,
} & MainButtonProps;

export default function MainButtonWithSave(props: Props) {
    const [saveData] = OpenApi.useUSave(props.schema);

    const onClick = () => {
        saveData(props.saveData, props.elementId).then(() => {
            if (props.onSaveCallback) {
                props.onSaveCallback();
            }
        });
    };


    return <MainButtonTpl {...props} onClick={onClick} />;
}
