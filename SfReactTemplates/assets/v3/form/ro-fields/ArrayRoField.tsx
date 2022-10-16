import { functions, UI } from '@newageerp/nae-react-ui';
import React, { Fragment } from 'react'
import { useTemplateLoader } from '../../templates/TemplateLoader';

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
    <UI.Form.ArrayRo
        title={""}
        schema={props.tabSchema}
        value={value}
        tab={functions.tabs.getTabFromSchemaAndType(props.tabSchema, props.tabType)}
      />
  )
}
