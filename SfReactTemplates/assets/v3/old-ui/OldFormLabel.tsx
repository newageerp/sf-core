import React, { Fragment } from 'react'
import ReactTooltip from 'react-tooltip';
import { v4 as uuidv4 } from 'uuid';

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

    const helpKey = uuidv4();

    return (
        <label className={className.join(" ")}>
            {props.help &&
                <Fragment>
                    <span
                        data-tip={props.help
                            .split("|||")
                            .map((e: any) => `<p>${e}</p>`)
                            .join("")}
                        data-for={"view-" + helpKey}
                        data-html={true}
                    >
                        <i className={"fad fa-info-circle text-blue-500 mr-1"} />
                    </span>
                    <ReactTooltip
                        id={"view-" + helpKey}
                        multiline={true}
                        type={"light"}
                        className={"rounded-md shadow-md text-left max-w-md"}
                    />
                </Fragment>
            }
            {props.text}
            {props.required && <span className={"text-red-500 ml-1"}>*</span>}
        </label>
    )
}
