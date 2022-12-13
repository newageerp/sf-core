import React, { Fragment } from 'react'
import { useUIBuilder } from '../../old-ui/builder/OldUIBuilderProvider';
import OldArrayFieldComponentRo from '../../old-ui/OldArrayFieldComponentRo';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';

interface Props {
  fieldKey: string;

  tabSchema: string;
  tabType: string;
}

export default function ArrayRoField(props: Props) {
  const {getTabFromSchemaAndType} = useUIBuilder();
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value: any[] = element[props.fieldKey] ? element[props.fieldKey] : []

  return (
    <OldArrayFieldComponentRo
        title={""}
        schema={props.tabSchema}
        value={value}
        tab={getTabFromSchemaAndType(props.tabSchema, props.tabType)}
      />
  )
}
