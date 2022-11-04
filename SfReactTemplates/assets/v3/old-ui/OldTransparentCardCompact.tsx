import React from 'react'

interface Props {
    children: any,
    className?: string,
    onClick?: () => void,
}

export default function OldTransparentCardCompact(props: Props) {
    return (
        <div className={"space-y-2 " + (props.className ? props.className : "")} onClick={props.onClick}>
            {props.children}
        </div>
    )
}
