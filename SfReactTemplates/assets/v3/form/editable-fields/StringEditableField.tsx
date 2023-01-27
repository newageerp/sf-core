import React, { Fragment, useEffect, useState } from "react";
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { Input } from "@newageerp/ui.form.base.form-pack";
import { FieldHexColor } from "@newageerp/v3.bundles.form-bundle";
import { Field } from "redux-orm/fields";

interface Props {
  fieldKey: string;
  as?: string,
}

export default function StringEditableField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element, updateElement } = tData;

  const [localVal, setLocalVal] = useState(element ? element[props.fieldKey] : '');

  const updateValue = (e: any) => setLocalVal(e.target.value);
  useEffect(() => {
    if (element && element[props.fieldKey] !== localVal) {
      setLocalVal(element[props.fieldKey]);
    }
  }, [element]);

  const onBlur = () => {
    updateElement(props.fieldKey, localVal)
  };

  useEffect(() => {
    const delayDebounceFn = setTimeout(() => {
      updateElement(props.fieldKey, localVal)
    }, 500)
    return () => clearTimeout(delayDebounceFn)
  }, [localVal])

  const handleKeyDown = (event: any) => {
    if (event.key === 'Enter') {
      onBlur();
    }
  };

  if (props.as === 'hex-color') {
    return <FieldHexColor
      className="tw3-w-full tw3-max-w-[500px]"
      value={localVal}
      onChange={(e) => {
        updateValue(e);
        updateElement(props.fieldKey, e.target.value)
      }}
      onBlur={onBlur}
      onKeyDown={handleKeyDown}
    />
  }

  return <Input
    className="tw3-w-full tw3-max-w-[500px]"
    value={localVal}
    onChange={updateValue}
    onBlur={onBlur}
    onKeyDown={handleKeyDown}
  />;
}
