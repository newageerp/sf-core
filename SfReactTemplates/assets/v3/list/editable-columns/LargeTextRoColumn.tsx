import React, { Fragment, useState } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { FieldTextarea } from '@newageerp/v3.bundles.form-bundle';
import { showSuccessNotification } from '../../navigation/NavigationComponent';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';

interface Props {
  fieldKey: string;
  as?: string;
  schema: string;
}

export default function LargeTextEditableColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  const [value, setValue] = useState(element ? element[props.fieldKey] : "");
  const updateValue = (e: any) => setValue(e.target.value);

  const [doSave] = OpenApi.useUSave(
    props.schema
  );

  if (!element) {
    return <Fragment />;
  }

  const saveElement = () => {
    doSave(
      {
        [props.fieldKey]: value,
      },
      element.id
    ).then(() => {
      showSuccessNotification("Saved");
    });
  };

  const onBlur = () => {
    saveElement();
  };

  const handleKeyDown = (event: any) => {
    if (event.key === 'Enter' && event.shiftKey) {
      onBlur();
    }
  };

  if (props.as === 'rich_editor') {
    return (
      <div>TODO</div>
    )
  } else {
    return (
      <FieldTextarea
        value={value}
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
