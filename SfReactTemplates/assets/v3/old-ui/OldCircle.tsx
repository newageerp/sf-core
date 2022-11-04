import React from 'react'
import { getTextColorForBg } from './OldButton';
import { BadgeBgColor } from './OldBadge';

export enum CircleSize {
    xs = 1,
    sm = 3,
    base = 4,
    lg = 5,
    xl = 6,
    xl2 = 7,
    xl3 = 8,
    xl4 = 9,
    xl5 = 10,
}

interface Props {
    bgColor?: BadgeBgColor | string
    brightness?: number
    opacity?: boolean,
    size: CircleSize,
    className?: string,
    children?: any,
}

export default function OldCircle(props: Props) {
    const className = ['rounded-full h-'+ props.size +' w-'+ props.size +' flex items-center justify-center']

    const brightness = props.brightness ? props.brightness : 500
    const bgColor = props.bgColor ? props.bgColor : BadgeBgColor.blue
    const bgColorClassName =
        'bg-' +
        (bgColor === BadgeBgColor.white || bgColor === BadgeBgColor.black
            ? bgColor
            : bgColor + '-' + brightness)
    const textColorClassName = getTextColorForBg(bgColor, brightness);

    className.push(bgColorClassName)
    className.push(textColorClassName)

    className.push('hover:bg-opacity-80')
    // className.push("active:bg-opacity-50");

    if (props.className) {
        className.push(props.className)
    }

    return (
        <div className={className.join(" ")}>{props.children}</div>
    )
}
