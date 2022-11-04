import React from 'react'
import { getTextColorForBg } from './OldButton';

export enum AlertSize {
    sm = "sm",
    base = "base",
    lg = "lg",
}

export enum AlertBgColor {
    black = 'black',
    white = 'white',
    gray = 'gray',
    red = 'red',
    yellow = 'yellow',
    green = 'green',
    blue = 'blue',
    indigo = 'indigo',
    purple = 'purple',
    pink = 'pink',
    nprimary = 'nprimary',
    nsecondary = 'nsecondary'
}

export const AlertBrightness = [
    50, 100, 200, 300, 400, 500, 600, 700, 800, 900
]

interface Props {
    bgColor?: AlertBgColor
    brightness?: number
    opacity?: boolean,
    size?: AlertSize,
    className?: string,
    children?: any,
    title?: string,
}


export default function OldAlert(props: Props) {
    const className = ['rounded-md'];

    const size = props.size ? props.size : AlertSize.base
    if (size === AlertSize.sm) {
        className.push('text-xs font-medium')
        className.push('px-2 py-2')
    } else if (size === AlertSize.lg) {
        className.push('text-base font-semibold')
        className.push('px-4 py-4')
    } else {
        className.push('text-sm font-medium')
        className.push('px-3 py-3')
    }

    const brightness = props.brightness ? props.brightness : 500
    const bgColor = props.bgColor ? props.bgColor : AlertBgColor.blue
    const bgColorClassName =
        'bg-' +
        (bgColor === AlertBgColor.white || bgColor === AlertBgColor.black
            ? bgColor
            : bgColor + '-' + brightness)
    const textColorClassName = getTextColorForBg(bgColor, brightness);

    className.push(bgColorClassName)
    className.push(textColorClassName)

    if (props.className) {
        className.push(props.className)
    }

    return (
        <div className={className.join(" ")} title={props.title}>{props.children}</div>
    )
}
