import React from 'react'
// import { UIConfig } from '../..'
// import FontAwesomeIcon, { FontAwesomeIconProps } from '../Icons/FontAwesomeIcon'
import PopoverConfirm from '../OldPopoverConfirm'
import { ButtonBgColor, ButtonSize, getTextColorForBg } from '../OldButton'
import {showSuccessNotification} from "../../navigation/NavigationComponent"

export interface ButtonUIBuilderProps
  extends React.DetailedHTMLProps<
    React.ButtonHTMLAttributes<HTMLButtonElement>,
    HTMLButtonElement
  > {
  icon?: any
  bgColor?: ButtonBgColor | string
  brightness?: number
  opacity?: number
  size?: ButtonSize | string
  forwardRef?: any
  textColorClassName?: string
  needPopoverConfirm?: boolean
  toastText?: string
}

export default function ButtonUIBuilder(props: ButtonUIBuilderProps) {
  const className = ['hover:underline']

  const size = props.size ? props.size : ButtonSize.base
  let textSizeClassName = ''
  let shadowClassName = ''
  if (size === ButtonSize.sm) {
    className.push('rounded-md')
    textSizeClassName = 'text-sm'
    shadowClassName = 'shadow-sm'
    className.push('px-2 py-2')
  } else if (size === ButtonSize.xs) {
    className.push('rounded-sm')
    textSizeClassName = 'text-xs'
    shadowClassName = 'shadow-sm'
    className.push('px-1 py-1')
  } else if (size === ButtonSize.lg) {
    className.push('rounded-lg')
    shadowClassName = 'shadow-md'
    textSizeClassName = 'text-lg'
    className.push('px-3 py-3')
  } else {
    className.push('rounded-md')
    shadowClassName = 'shadow-sm'
    textSizeClassName = 'text-base'
    className.push('px-2 py-2')
  }
  if (!props.className || props.className?.indexOf('text-') === -1) {
    className.push(textSizeClassName)
  }
  if (!props.className || props.className?.indexOf('shadow-') === -1) {
    className.push(shadowClassName)
  }
  const brightness = props.brightness ? props.brightness : 500
  const bgColor = props.bgColor ? props.bgColor : ButtonBgColor.blue
  const bgColorClassName =
    'bg-' +
    (bgColor === ButtonBgColor.white || bgColor === ButtonBgColor.black
      ? bgColor
      : bgColor + '-' + brightness)
  const textColorClassName = getTextColorForBg(bgColor, brightness)

  className.push(bgColorClassName)
  if (props.textColorClassName) {
    className.push(props.textColorClassName)
  } else {
    className.push(textColorClassName)
  }

  className.push('hover:bg-opacity-80')
  // className.push("active:bg-opacity-50");

  if (props.opacity && props.opacity < 100) {
    className.push('bg-opacity-' + props.opacity)
  }

  if (props.icon) {
    className.push('text-left')
  }
  if (props.className) {
    className.push(props.className)
  }

  const iconProps = props.icon ? { ...props.icon } : undefined
  if (iconProps) {
    const iconClassName = []
    if (iconProps.className) {
      iconClassName.push(iconProps.className)
    }
    if (!!props.children) {
      iconClassName.push('mr-1')
    }

    if (props.textColorClassName) {
      iconClassName.push(props.textColorClassName)
    } else {
      iconClassName.push(textColorClassName)
    }
    iconProps.className = iconClassName.join(' ')
  }

  const buttonProps = { ...props }
  delete buttonProps.bgColor
  delete buttonProps.brightness
  delete buttonProps.opacity
  delete buttonProps.size
  delete buttonProps.forwardRef
  delete buttonProps.icon;

  const onClick = buttonProps.onClick
    ? () => {
        // @ts-ignore
        props.onClick()
        if (props.toastText) {
            showSuccessNotification(props.toastText)
        }
      }
    : () => {}

  delete buttonProps.onClick

  if (props.needPopoverConfirm) {
    return (
      <PopoverConfirm onClick={onClick}>
        <button
          {...buttonProps}
          className={className.join(' ')}
          ref={props.forwardRef}
        >
          {/* {!!iconProps && <FontAwesomeIcon {...iconProps} />} */}
          {props.children}
        </button>
      </PopoverConfirm>
    )
  }

  return (
    <button
      {...buttonProps}
      className={className.join(' ')}
      ref={props.forwardRef}
      onClick={onClick}
    >
      {/* {!!iconProps && <FontAwesomeIcon {...iconProps} />} */}
      {props.children}
    </button>
  )
}
