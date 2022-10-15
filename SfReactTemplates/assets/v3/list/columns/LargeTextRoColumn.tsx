import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { Text } from '@newageerp/data.table.text';

interface Props {
  fieldKey: string;
  as?: string;
}

export default function LargeTextRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
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
