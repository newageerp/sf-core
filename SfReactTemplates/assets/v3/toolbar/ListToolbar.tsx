import React, { Fragment } from 'react'
import { Template, TemplatesParser } from '@newageerp/v3.templates.templates-core'

interface Props {
    toolbarLeft: Template[],
    toolbarRight: Template[],
    toolbarMiddle: Template[],
}

export default function ListToolbar(props: Props) {
    if (props.toolbarLeft.length === 0 && props.toolbarRight.length === 0 && props.toolbarMiddle.length === 0) {
        return <Fragment />
    }
    return (
        <div className='tw3-flex tw3-gap-6 tw3-items-center'>
            <div className='tw3-flex tw3-gap-2 tw3-items-center'>
                <TemplatesParser templates={props.toolbarLeft} />
            </div>
            <div className='tw3-flex-grow tw3-flex tw3-gap-2 tw3-items-center'>
                <TemplatesParser templates={props.toolbarMiddle} />
            </div>
            <div className='tw3-justify-end tw3-flex tw3-gap-2 tw3-items-center'>
                <TemplatesParser templates={props.toolbarRight} />
            </div>
        </div>
    )
}
