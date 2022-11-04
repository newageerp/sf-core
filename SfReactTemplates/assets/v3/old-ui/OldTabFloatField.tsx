import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import React, {useState, useEffect} from 'react';
import OldInputField from './OldInputField';


interface Props {
    elementId: number,
    value: number,
    property: any,
    onEditCallback?: (el: any) => void,
}

export default function OldTabFloatField(props: Props) {
    const [q, setQ] = useState(props.value.toString());

    const [doSave] = OpenApi.useUSave(
      props.property.schema
    );
    const saveElement = () => {
      doSave(
        {
          [props.property.key]: parseFloat(q),
        },
        props.elementId
      ).then(() => {
        if (props.onEditCallback) {
          props.onEditCallback({id: props.elementId});
        }
      });
    };
  
    useEffect(() => {
      setQ(props.value.toString());
    }, [props.value]);
  
    return <OldInputField value={q} onChange={(e) => setQ(e.target.value)} onBlur={saveElement} />;
}
