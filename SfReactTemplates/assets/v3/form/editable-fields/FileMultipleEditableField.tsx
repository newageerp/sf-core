import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import OldFileFieldMultiple from '../../old-ui/OldFileFieldMultiple';

interface Props {
  fieldKey: string;
}

export default function FileMultipleEditableField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValue = (e: any) => updateElement(props.fieldKey, e);

  return (
    <OldFileFieldMultiple
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
