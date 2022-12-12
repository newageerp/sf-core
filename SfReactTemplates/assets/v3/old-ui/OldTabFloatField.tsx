import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { InputFloat } from '@newageerp/ui.form.base.form-pack';
import { useDidMount } from '@newageerp/v3.bundles.hooks-bundle';
import React, { useState, useEffect } from 'react';


interface Props {
  elementId: number,
  value: number,
  property: any,
  onEditCallback?: (el: any) => void,
}

export default function OldTabFloatField(props: Props) {
  const isMount = useDidMount();
  const [q, setQ] = useState(props.value);

  const [doSave] = OpenApi.useUSave(
    props.property.schema
  );
  const saveElement = () => {
    doSave(
      {
        [props.property.key]: q,
      },
      props.elementId
    ).then(() => {
      if (props.onEditCallback) {
        props.onEditCallback({ id: props.elementId });
      }
    });
  };

  useEffect(() => {
    if (!isMount) {
      saveElement();
    }
  }, [q]);

  useEffect(() => {
    setQ(props.value);
  }, [props.value]);

  return <InputFloat value={q} onChangeFloat={(e) => setQ(e)} />;
}
