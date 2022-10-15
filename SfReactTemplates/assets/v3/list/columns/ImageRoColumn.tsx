import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';

interface Props {
  fieldKey: string;
}

export default function ImageRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <img src={value} className={'tw3-w-56'} />
  )
}
