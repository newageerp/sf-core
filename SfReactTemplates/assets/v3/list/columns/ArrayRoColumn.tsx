import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';

interface Props {
  fieldKey: string;
}

export default function ArrayRoColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  return (
    <div>ArrayRoField</div>
  )
}
