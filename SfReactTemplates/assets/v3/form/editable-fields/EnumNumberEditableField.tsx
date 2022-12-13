import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { FieldSelect } from '@newageerp/v3.bundles.form-bundle'

interface Props {
  fieldKey: string;
  options: any[];
}

export default function EnumNumberEditableField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValue = (e: any) => updateElement(props.fieldKey, e);

  return (
    <FieldSelect
      value={value}
      onChange={updateValue}
      options={props.options}
      className="tw3-w-full tw3-max-w-[500px]"
      icon='bars'
    />
  )
}
