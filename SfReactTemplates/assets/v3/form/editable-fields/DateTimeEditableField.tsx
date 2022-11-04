import React, { Fragment } from 'react'
import OldDateTimeField from '../../old-ui/OldDateTimeField';
import { useTemplateLoader } from '../../templates/TemplateLoader';

interface Props {
  fieldKey: string;
}

export default function DateTimeEditableField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValue = (e: any) => updateElement(props.fieldKey, e);

  return (
    <OldDateTimeField
      value={value}
      onChange={updateValue}
    />
  )
}
