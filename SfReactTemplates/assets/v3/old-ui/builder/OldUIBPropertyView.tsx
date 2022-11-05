import React, { Fragment } from 'react'
import { getPropertyForPath } from '../../utils'
import { useBuilderWidget } from './OldBuilderWidgetProvider'
import PropertyViewWidget from './OldPropertyViewWidget'


interface Props {
  path: string
  titlePath?: string
  showLabel?: boolean
  isCompactView?: boolean
  contentClassName?: string
  labelClassName?: string
}

export default function UIBPropertyView(props: Props) {
  const { element } = useBuilderWidget()

  const titlePath = props.titlePath ? props.titlePath : props.path

  const property = getPropertyForPath(props.path)

  const isSingle = props.path.split('.').length === 2

  if (!property) {
    return <Fragment />
  }

  if (isSingle) {
    return (
      <PropertyViewWidget
        propertyPath={props.path}
        property={property}
        id={element.id}
        contentClassName={props.contentClassName}
        label={
          props.showLabel
            ? {
              titlePath: titlePath,
              isCompactView: props.isCompactView,
              className: props.labelClassName
            }
            : undefined
        }
      />
    )
  }

  const keypathArr = props.path.split('.').splice(1)
  keypathArr[keypathArr.length - 1] = 'id'
  const keypath = keypathArr.join('.')

  let elementId = -1
  try {
    elementId = keypath
      .split('.')
      .reduce((previous, current) => previous[current], element)
  } catch (e) { }

  return (
    <PropertyViewWidget
      propertyPath={props.path}
      property={property}
      id={elementId}
      contentClassName={props.contentClassName}
      label={
        props.showLabel
          ? {
            titlePath: titlePath,
            isCompactView: props.isCompactView,
            className: props.labelClassName
          }
          : undefined
      }
    />
  )
}
