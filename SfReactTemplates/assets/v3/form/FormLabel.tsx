import React from 'react'
import {Label} from '@newageerp/v3.bundles.form-bundle'

interface Props {
  text: string,
  paddingTop?: string,
}

export default function FormLabel(props: Props) {
  return (
      <Label paddingTop={props.paddingTop}>
        {props.text}
      </Label>
  )
}
