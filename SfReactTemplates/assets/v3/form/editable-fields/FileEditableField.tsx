import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import OldFileField from '../../old-ui/OldFileField';

interface Props {
  fieldKey: string;
}

export default function FileEditableField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValue = (e: any) => updateElement(props.fieldKey, e);

  return (
    <OldFileField
      val={value}
      onChange={updateValue}
      // @ts-ignore
      property={{
        key: props.fieldKey,
        schema: 'file-upload',
      }}
    />
  )
}
