import React, { Fragment } from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import { String } from '@newageerp/data.table.string';
import { RsButton } from '@newageerp/v3.buttons.rs-button';
import { functions } from '@newageerp/nae-react-ui';

interface Props {
  id: number,
  idPath?: string,
  fieldKey: string;
  relSchema?: string;
}

export default function ObjectDfRoField(props: Props) {
  let idPath = props.idPath;
  if (!idPath) {
    const fA = props.fieldKey.split(".");
    fA[fA.length - 1] = 'id';
    idPath = fA.join(".");
  }
  let relSchema = props.relSchema;
  if (!relSchema) {
    const prop = functions.properties.getPropertyForPath(props.fieldKey);
    relSchema = prop?.schema;
  }

  const value = useDfValue({
    path: props.fieldKey,
    id: props.id
  });
  const idValue = useDfValue({
    path: idPath,
    id: props.id
  });

  if (!relSchema) {
    return <Fragment />
  }

  if (!idValue) {
    return <Fragment />
  }

  return (
    <RsButton
      id={idValue}
      schema={relSchema}
      button={{
        children: <String value={value} />,
        color: 'white',
        skipPadding: true,
      }}
    />

  )
}
