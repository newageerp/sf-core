import React from 'react'
import { ToolbarButton as ToolbarButtonTpl } from '@newageerp/v3.bundles.buttons-bundle'
import { Template, TemplatesParser, useTemplateLoader } from '../templates/TemplateLoader';
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
    elementId: number,
    extraRequestData?: any
}

export default function ToolbarButtonElementWithAction(props: Props) {
    const onClick = () => {
        axiosInstance.post(
            props.actionPath,
            {
                id: props.elementId,
                ...props.extraRequestData
            }
        )
    }
    return (
        <ToolbarButtonTpl
            {...props.button}
            onClick={onClick}
        />
    )
}

