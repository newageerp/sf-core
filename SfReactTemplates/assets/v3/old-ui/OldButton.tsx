import React from 'react'

export enum ButtonBgColor {
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

export const ButtonBrightness = [
  50, 100, 200, 300, 400, 500, 600, 700, 800, 900
]

export const ButtonOpacity = [
  10, 20, 30, 40, 50, 60, 70, 80, 90, 100
]

export enum ButtonSize {
  xs = 'xs',
  sm = 'sm',
  base = 'base',
  lg = 'lg'
}

enum TextColor {
  black = 'white',
  white = 'black',
  gray = 'gray',
  red = 'white',
  yellow = 'gray-800',
  green = 'white',
  blue = 'white',
  indigo = 'white',
  purple = 'white',
  pink = 'white',
  nprimary = 'white',
  nsecondary = 'white'
}

export const getTextColorForBg = (bgColor: string, brightness: number) => {
    // @ts-ignore
  let textColorClassName = 'text-' + TextColor[bgColor]
  if (bgColor === ButtonBgColor.white) {
    return textColorClassName;
  }
  if (brightness <= 400) {
    // if (bgColor !== ButtonBgColor.gray && bgColor !== ButtonBgColor.yellow) {
    let textColorBrightness = Math.max(500 + brightness, 600);
    textColorClassName = 'text-' + bgColor + '-' + textColorBrightness
    // }
  }
  if (brightness > 400) {
    textColorClassName = 'text-white'
  }

  return textColorClassName;
}

export interface ButtonProps
  extends React.DetailedHTMLProps<
  React.ButtonHTMLAttributes<HTMLButtonElement>,
  HTMLButtonElement
  > {
  bgColor?: ButtonBgColor | string
  brightness?: number
  opacity?: number
  size?: ButtonSize | string
  icon?: string,
  iconClassName?: string,
  iconLoading?: boolean,
  forwardRef?: any,
  textColorClassName?: string,
}

export default function OldButton(props: ButtonProps) {
  const className = ['hover:underline']

  const size = props.size ? props.size : ButtonSize.base
  let textSizeClassName = "";
  let shadowClassName = "";
  if (size === ButtonSize.sm) {
    className.push('rounded-md')
    textSizeClassName = "text-sm"
    shadowClassName = "shadow-sm"
    className.push('px-2 py-2')
  } else if (size === ButtonSize.xs) {
    className.push('rounded-sm')
    textSizeClassName = "text-xs"
    shadowClassName = "shadow-sm"
    className.push('px-1 py-1')
  } else if (size === ButtonSize.lg) {
    className.push('rounded-lg')
    shadowClassName = "shadow-md"
    textSizeClassName = "text-lg"
    className.push('px-3 py-3')
  } else {
    className.push('rounded-md')
    shadowClassName = "shadow-sm"
    textSizeClassName = "text-base"
    className.push('px-2 py-2')
  }
  if (!props.className || props.className?.indexOf("text-") === -1) {
    className.push(textSizeClassName)
  }
  if (!props.className || props.className?.indexOf("shadow-") === -1) {
    className.push(shadowClassName)
  }
  const brightness = props.brightness ? props.brightness : 500
  const bgColor = props.bgColor ? props.bgColor : ButtonBgColor.blue
  const bgColorClassName =
    'bg-' +
    (bgColor === ButtonBgColor.white || bgColor === ButtonBgColor.black
      ? bgColor
      : bgColor + '-' + brightness)
  const textColorClassName = getTextColorForBg(bgColor, brightness);

  className.push(bgColorClassName)
  if (props.textColorClassName) {
    className.push(props.textColorClassName);
  } else {
    className.push(textColorClassName)
  }

  className.push('hover:bg-opacity-80')
  // className.push("active:bg-opacity-50");

  if (props.opacity && props.opacity < 100) {
    className.push("bg-opacity-" + props.opacity);
  }

  if (props.icon) {
    className.push("text-left");
  }
  if (props.className) {
    className.push(props.className)
  }

  const iconClassName = [textColorClassName];
  if (props.icon) {
    if (props.iconLoading) {
      iconClassName.push("animate-spin " + props.icon);
    } else {
      iconClassName.push(props.icon);
    }
  }
  if (props.children) {
    iconClassName.push("mr-1");
  }
  if (props.iconClassName) {
    iconClassName.push(props.iconClassName);
  }

  const buttonProps = { ...props };
  delete buttonProps.bgColor;
  delete buttonProps.brightness;
  delete buttonProps.opacity;
  delete buttonProps.size;
  delete buttonProps.icon;
  delete buttonProps.iconClassName;
  delete buttonProps.iconLoading;
  delete buttonProps.forwardRef;
  return (
    <button {...buttonProps} className={className.join(' ')} ref={props.forwardRef}>
      {props.icon && <i className={iconClassName.join(" ")} />}
      {props.children}
    </button>
  )
}