import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import {Int} from '@newageerp/data.table.int'

interface Props {
  fieldKey: string;
}

export default function NumberRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <Int value={value}/>
  )
}
