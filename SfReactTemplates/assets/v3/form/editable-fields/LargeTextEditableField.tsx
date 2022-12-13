import { FieldTextarea } from '@newageerp/v3.bundles.form-bundle';
import React, { Fragment, useEffect, useState } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { RichEditor } from '@newageerp/ui.form.base.form-pack';

interface Props {
  fieldKey: string;
  as?: string;
}

export default function LargeTextEditableField(props: Props) {
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
    if (event.key === 'Enter' && event.shiftKey) {
      onBlur();
    }
  };

  if (!element) {
    return <Fragment />;
  }

  const value = element[props.fieldKey];
  const updateValueRich = (e: any) => updateElement(props.fieldKey, e);

  if (props.as === 'rich_editor') {
    return (
      <RichEditor
        value={value}
        setValue={updateValueRich}
        className={'w-full block'}
      />
    )
  } else {
    return (
      <FieldTextarea
        value={localVal}
        onChange={updateValue}
        className={'tw3-w-full tw3-block'}
        autoRows={true}
        rows={2}
        onBlur={onBlur}
        onKeyDown={handleKeyDown}
      />
    )
  }
}
