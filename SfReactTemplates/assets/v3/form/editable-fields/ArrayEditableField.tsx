import React, { Fragment } from 'react'
import OldArrayFieldComponent from '../../old-ui/OldArrayFieldComponent';
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { getTabFromSchemaAndType } from '../../utils';

interface Props {
  fieldKey: string;

  tabSchema: string;
  tabType: string;
}

export default function ArrayEditableField(props: Props) {
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
    />
  )
}
