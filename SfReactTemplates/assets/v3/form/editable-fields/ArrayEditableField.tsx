import React, { Fragment } from 'react'
import { useUIBuilder } from '../../old-ui/builder/OldUIBuilderProvider';
import OldArrayFieldComponent from '../../old-ui/OldArrayFieldComponent';
import { Template, useTemplateLoader } from '../../templates/TemplateLoader';

interface Props {
  fieldKey: string;

  tabSchema: string;
  tabType: string;

  toolbar: Template[],
}

export default function ArrayEditableField(props: Props) {
  const {getTabFromSchemaAndType} = useUIBuilder();

  const { data: tData } = useTemplateLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value: any[] = element[props.fieldKey] ? element[props.fieldKey] : []
  const updateValue = (e: any) => {
    console.log('array editable', props.fieldKey, e);

    updateElement(props.fieldKey, e);
  }

  return (
    <OldArrayFieldComponent
      schema={props.tabSchema}
      title=""
      value={value}
      onChange={updateValue}
      tab={getTabFromSchemaAndType(props.tabSchema, props.tabType)}
      parentElement={element}
      toolbar={props.toolbar}
    />
  )
}
