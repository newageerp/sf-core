import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';

interface Props {
  fieldKey: string;
}

export default function EnumMultiTextRoField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  return (
    <div>EnumMultiTextRoField</div>
  )
}
