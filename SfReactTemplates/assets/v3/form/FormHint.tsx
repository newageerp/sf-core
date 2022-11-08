import React from 'react'
import { FieldHint } from '@newageerp/v3.bundles.form-bundle'

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
