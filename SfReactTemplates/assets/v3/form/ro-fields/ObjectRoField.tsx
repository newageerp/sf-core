import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { String } from '@newageerp/data.table.base';
import { RsButton } from '@newageerp/v3.bundles.buttons-bundle';

interface Props {
  fieldKey: string;
  fieldSchema: string;

  relKey: string;
  relSchema: string;

  as?: string;

  disableLink?: boolean;
}

export default function ObjectRoField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = props.fieldKey in element && element[props.fieldKey] && props.relKey in element[props.fieldKey] ? element[props.fieldKey][props.relKey] : '';
  const elementId = props.fieldKey in element && element[props.fieldKey] && props.relKey in element[props.fieldKey] ? element[props.fieldKey].id : 0;

  if (!elementId) {
    return <Fragment />
  }

  if (props.as === 'select' || props.disableLink) {
    return <String value={value} />
  }

  return (
    <RsButton
      id={elementId}
      schema={props.relSchema}
      button={{
        children: <String value={value} />,
        color: 'white',
        skipPadding: true,
      }}
    />
  )
}
