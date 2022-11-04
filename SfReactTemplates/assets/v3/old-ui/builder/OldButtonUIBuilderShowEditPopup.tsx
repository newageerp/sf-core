import React from 'react'
import { useBuilderWidget } from './OldBuilderWidgetProvider'
import ButtonUIBuilder, { ButtonUIBuilderProps } from './OldButtonUIBuilder'
import { SFSOpenEditModalWindowProps } from '@newageerp/v3.popups.mvc-popup'

interface Props {
  button: ButtonUIBuilderProps
  schema: string
  editType: string
  skipHiddenCheck?: string
}

export default function ButtonUIBuilderShowEditPopup(props: Props) {
  const parentElement = useBuilderWidget().element

  const doAction = () => {
    SFSOpenEditModalWindowProps({
      id: parentElement.id,
      schema: props.schema,
      type: props.editType,
    //   skipHiddenCheck: props.skipHiddenCheck === '1' // TODO
    })
  }

  return <ButtonUIBuilder {...props.button} onClick={doAction} />
}
