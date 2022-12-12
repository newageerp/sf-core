import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import {Datetime} from '@newageerp/data.table.base'

interface Props {
  fieldKey: string;
}

export default function DateTimeRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <Datetime value={value}/>
  )
}
