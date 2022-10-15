import React from 'react'
import StringEditableField from './StringEditableField';

interface Props {
  fieldKey: string;
}

export default function AudioEditableField(props: Props) {
  return (
    <StringEditableField {...props} />
  )
}
