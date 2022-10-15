import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import {String} from '@newageerp/data.table.string';

interface Props {
  fieldKey: string;
}

export default function StringRoField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <String value={value} />
  )
}
