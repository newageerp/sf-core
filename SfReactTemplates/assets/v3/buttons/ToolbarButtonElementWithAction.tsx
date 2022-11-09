import React from 'react'
import { ToolbarButton as ToolbarButtonTpl } from '@newageerp/v3.bundles.buttons-bundle'
import { Template, TemplatesParser } from '../templates/TemplateLoader';

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
}

export default function ToolbarButtonElementWithAction(props: Props) {
    const onClick = () => {
    }
    return (
        <ToolbarButtonTpl
            {...props.button}
            onClick={onClick}
        />
    )
}

