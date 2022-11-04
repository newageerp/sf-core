import React, { Fragment } from "react";
import OldSelectFieldMulti from "../../old-ui/OldSelectFieldMulti";
import { useTemplateLoader } from "../../templates/TemplateLoader";

interface Props {
  fieldKey: string;
  options: any[];
}

export default function EnumMultiNumberEditableField(props: Props) {
  const { data: tData } = useTemplateLoader();
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
