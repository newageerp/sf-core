import React from 'react'
import { Wide } from '@newageerp/ui.form.block.wide'
import { Template, TemplatesParser } from '../templates/TemplateLoader'

interface Props {
  children: Template[]
}

export default function RoForm(props: Props) {
  return (
    <Wide className='tw3-text-sm'>
      <TemplatesParser templates={props.children} />
    </Wide>
  )
}
