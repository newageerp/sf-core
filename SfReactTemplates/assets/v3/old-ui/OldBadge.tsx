import React from 'react'
import { getTextColorForBg } from './OldButton'

export enum BadgeSize {
  xs = 'xs',
  sm = 'sm',
  base = 'base',
  lg = 'lg'
}

export enum BadgeBgColor {
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

export const BadgeBrightness = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900]

interface Props {
  bgColor?: BadgeBgColor | string
  brightness?: number
  opacity?: boolean
  size?: BadgeSize | string
  className?: string
  children?: any
  title?: string
}

export default function OldBadge(props: Props) {
  const className = ['rounded-full']

  const size = props.size ? props.size : BadgeSize.base
  if (size === BadgeSize.sm) {
    className.push('text-xs font-light uppercase text-center')
    className.push('px-2 py-1')
  } else if (size === BadgeSize.lg) {
    className.push('text-base font-semibold uppercase text-center')
    className.push('px-6 py-3')
  } else if (size === BadgeSize.xs) {
    className.push('text-xxxs font-light uppercase text-center')
    className.push('px-2 py-1')
  } else {
    className.push('text-sm uppercase text-center')
    className.push('px-2 py-1')
  }

  const brightness = props.brightness ? props.brightness : 500
  const bgColor = props.bgColor ? props.bgColor : BadgeBgColor.blue
  const bgColorClassName =
    'bg-' +
    (bgColor === BadgeBgColor.white || bgColor === BadgeBgColor.black
      ? bgColor
      : bgColor + '-' + brightness)
  const textColorClassName = getTextColorForBg(bgColor, brightness)

  className.push(bgColorClassName)
  className.push(textColorClassName)

  className.push('hover:bg-opacity-80')
  // className.push("active:bg-opacity-50");

  if (props.className) {
    className.push(props.className)
  }

  return (
    <div className={className.join(' ')} title={props.title}>
      {props.children}
    </div>
  )
}
