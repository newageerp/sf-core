import React from 'react'
import { MultipleFilesWidget, MultipleFilesWidgetItem } from '@newageerp/ui.ui-bundle';

interface Props {
    items: MultipleFilesWidgetItem[],
}

export default function ViewFilesWidget(props: Props) {
  return (
    <MultipleFilesWidget items={props.items} />
  )
}
