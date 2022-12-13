import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { Text } from '@newageerp/data.table.base';

interface Props {
  fieldKey: string;
  as?: string;
}

export default function LargeTextRoField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];

  if (props.as === 'rich_editor') {
    return (
      <div>TODO</div>
    )
  } else {
    return (
      <Text
        value={value}
      />
    )
  }
}
