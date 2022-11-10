import React, { Fragment } from 'react'
import { Tooltip } from '@newageerp/v3.bundles.badges-bundle'

interface Props {
    text: string,
    help?: string,
    size?: LabelSize | string,
    className?: string,
    required?: boolean
}

export enum LabelSize {
    sm = 'sm',
    base = 'base',
    lg = 'lg'
}

export default function OldFormLabel(props: Props) {
    const className = [""];

    const size = props.size ? props.size : LabelSize.base
    if (size === LabelSize.sm) {
        className.push('text-sm')
    } else if (size === LabelSize.lg) {
        className.push('text-lg')
    } else {
        className.push('text-base')
    }
    if (props.className) {
        className.push(props.className);
    }

    return (
        <label className={className.join(" ")}>
            {props.help &&
                <Fragment>
                    <Tooltip text={props.help} />
                </Fragment>
            }
            {props.text}
            {props.required && <span className={"text-red-500 ml-1"}>*</span>}
        </label>
    )
}
