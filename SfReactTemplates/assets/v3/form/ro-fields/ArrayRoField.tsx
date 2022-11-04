import React, { Fragment } from 'react'
import OldArrayFieldComponentRo from '../../old-ui/OldArrayFieldComponentRo';
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { getTabFromSchemaAndType } from '../../utils';

interface Props {
  fieldKey: string;

  tabSchema: string;
  tabType: string;
}

export default function ArrayRoField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  const value: any[] = element[props.fieldKey] ? element[props.fieldKey] : []

  if (!element) {
    return <Fragment />;
  }

  return (
    <OldArrayFieldComponentRo
        title={""}
        schema={props.tabSchema}
        value={value}
        tab={getTabFromSchemaAndType(props.tabSchema, props.tabType)}
      />
  )
}
