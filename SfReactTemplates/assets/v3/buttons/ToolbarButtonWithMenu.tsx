import React from 'react'
import { ToolbarButtonWithMenu as ToolbarButtonWithMenuTpl } from '@newageerp/v3.bundles.buttons-bundle'
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
    menu: {
        isAbsolute?: boolean,
        children: Template[],
    },
}

export default function ToolbarButtonWithMenu(props: Props) {
    return (
        <ToolbarButtonWithMenuTpl
            button={
                props.button
            }
            menu={
                {
                    isAbsolute: props.menu.isAbsolute,
                    children: <TemplatesParser templates={props.menu.children} />
                }
            }
        />
    )
}

