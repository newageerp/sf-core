import React, { Fragment, useEffect, useState } from "react";
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { Input } from "@newageerp/ui.form.base.form-pack";

interface Props {
  fieldKey: string;
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

  const handleKeyDown = (event: any) => {
    if (event.key === 'Enter') {
      onBlur();
    }
  };

  return <Input
    className="tw3-w-full tw3-max-w-[500px]"
    value={localVal}
    onChange={updateValue}
    onBlur={onBlur}
    onKeyDown={handleKeyDown}
  />;
}
