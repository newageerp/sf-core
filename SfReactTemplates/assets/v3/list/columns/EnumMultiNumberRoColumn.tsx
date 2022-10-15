import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';

interface Props {
  fieldKey: string;
}

export default function EnumMultiNumberRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  return (
    <div>EnumMultiNumberRoField</div>
  )
}
