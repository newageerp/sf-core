import React from 'react'
import OldFilesWidget from '../OldFilesWidget'
import { useBuilderWidget } from './OldBuilderWidgetProvider'

interface Props {
  readOnly?: boolean,
  skipHeader?: boolean,
  actions?: string,
  title: string,
  schema: string,
  type: string,
  className?: string,
  hint?: string,
}

export default function FilesWidgetUIBuilder(props: Props) {
  const parentElement = useBuilderWidget().element
  return <OldFilesWidget
    options={{
      readOnly: props.readOnly,
      skipHeader: props.skipHeader,
      actions: props.actions ? JSON.parse(props.actions) : undefined,
      type: props.type,
      className: props.className,
      hint: props.hint,
      title: props.title
    }}
    schema={props.schema}
    element={{
      id: parentElement.id
    }}
  />
}
