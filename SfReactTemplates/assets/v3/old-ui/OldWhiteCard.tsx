import React from 'react'

interface Props {
    children: any,
    className?: string,
    onClick?: () => void,
    style?: any,
}

export default function OldWhiteCard(props: Props) {
    return (
        <div style={props.style} className={"space-y-4 p-2 shadow-md bg-white rounded-md " + (props.className ? props.className : "")} onClick={props.onClick}>
            {props.children}
        </div>
    )
}
