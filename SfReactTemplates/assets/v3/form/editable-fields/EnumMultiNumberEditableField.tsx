import React, { Fragment } from "react";
import OldSelectFieldMulti from "../../old-ui/OldSelectFieldMulti";
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';

interface Props {
  fieldKey: string;
  options: any[];
}

export default function EnumMultiNumberEditableField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element, updateElement } = tData;

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValue = (e: any) => updateElement(props.fieldKey, e);

  return (
    <OldSelectFieldMulti
      value={value}
      onChange={updateValue}
      options={props.options.map((e: any) => {
        return { ...e, value: parseInt(e.value, 10) };
      })}
      className="tw3-w-full tw3-max-w-[500px]"
      // icon='bars'
      isMulti={true}
    />
  );
}
