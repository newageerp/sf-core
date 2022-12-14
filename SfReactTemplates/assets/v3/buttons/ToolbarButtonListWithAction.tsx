import React from 'react'
import { ToolbarButton as ToolbarButtonTpl } from '@newageerp/v3.bundles.buttons-bundle'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { axiosInstance } from '../api/config';

interface Props {
    button: {
        title?: string,
        iconName: string,
        color: string,
        className?: string,
        disabled?: boolean,
        children?: string,
        confirmation?: boolean,
    },
    actionPath: string,
    extraRequestData?: any
}

export default function ToolbarButtonListWithAction(props: Props) {
    const { data: tData } = useTemplatesLoader();

    const onClick = () => {
        axiosInstance.post(
            props.actionPath,
            {
                ...props.extraRequestData
            }
        ).then(() => {
            if (tData.reloadData) {
                tData.reloadData();
            }
        });
    }
    return (
        <ToolbarButtonTpl
            {...props.button}
            onClick={onClick}
        />
    )
}

