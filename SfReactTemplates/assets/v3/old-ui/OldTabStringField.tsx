import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { FieldInput } from '@newageerp/v3.bundles.form-bundle';
import React, { useState, useEffect } from 'react';

interface Props {
    elementId: number,
    value: string,
    property: any,
    onEditCallback?: (el: any) => void,
}

export default function OldTabStringField(props: Props) {
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
        setQ(props.value);
    }, [props.value]);

    return <FieldInput value={q} onChange={(e) => setQ(e.target.value)} onBlur={saveElement} />;
}
