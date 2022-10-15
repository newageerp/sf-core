import React from 'react'
import { FieldHint } from '@newageerp/v3.form.field-hint'

interface Props {
  text: string,
}

export default function FormHint(props: Props) {
  return (
    <FieldHint>
      {props.text}
    </FieldHint>
  )
}
