import React, { useEffect, useState } from 'react';

import {FieldSelect} from '@newageerp/v3.bundles.form-bundle'
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';

interface Props {
  elementId: number,
  value: number,
  property: any,
  options: any,
  onEditCallback?: (el: any) => void,
}

export default function OldTabSelectField(props: Props) {
  const [localVal, setLocalVal] = useState(props.value);

  useEffect(() => {
    setLocalVal(props.value);
  }, [props.value]);

  const [doSave] = OpenApi.useUSave(
    props.property.schema
  );
  const saveElement = (q: any) => {
    setLocalVal(q);
    doSave(
      {
        [props.property.key]: q,
      },
      props.elementId
    ).then(() => {
      if (props.onEditCallback) {
        props.onEditCallback({id: props.elementId});
      }
    });
  };

  return (<FieldSelect
    value={localVal}
    onChange={(e) => saveElement(e)}
    options={props.options}
    className={'w-96'}
  />);
}
