import { OpenApi } from '@newageerp/nae-react-auth-wrapper'
import React, { Fragment } from 'react'
import { getPropertyForPath } from '../../utils'
import { useBuilderWidget } from './OldBuilderWidgetProvider'
import ButtonUIBuilder, { ButtonUIBuilderProps } from './OldButtonUIBuilder'


interface Props {
  buttonStatus0: ButtonUIBuilderProps
  buttonStatus10: ButtonUIBuilderProps
  path: string
  parentElement?: any
}

export default function ButtonUIBuilderToggleNumber(props: Props) {
  const bwElement = useBuilderWidget().element;

  const parentElement = props.parentElement
    ? props.parentElement
    : bwElement

  const property = getPropertyForPath(props.path)
  const schema = property ? property.schema : '-'

  const [doReq, doReqParams] = OpenApi.useUSave(schema)

  const doAction = () => {
    const _key = property ? property.key : ''
    doReq(
      {
        [_key]: val === 0 ? 10 : 0
      },
      parentElement.id
    )
  }

  if (!property) {
    return <Fragment />
  }
  const val = parentElement[property.key]

  let buttonProps0 = { ...props.buttonStatus0 }
  if (buttonProps0.icon) {
    buttonProps0.icon.rotate = doReqParams.loading
  }

  let buttonProps10 = { ...props.buttonStatus10 }
  if (buttonProps10.icon) {
    buttonProps10.icon.rotate = doReqParams.loading
  }

  if (val === 0) {
    return <ButtonUIBuilder {...props.buttonStatus0} onClick={doAction} />
  }
  return <ButtonUIBuilder {...props.buttonStatus10} onClick={doAction} />
}
