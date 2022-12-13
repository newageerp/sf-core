import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';

interface Props {
  fieldKey: string;
}

export default function AudioRoColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  return (
    <audio controls>
      <source src={value} type='audio/mp3' />
      Your browser does not support the audio element.
    </audio>
  )
}
