import React, { Fragment } from 'react'
import { useDfValue } from '../../hooks/useDfValue';
import { String } from '@newageerp/data.table.base';
import { RsButton } from '@newageerp/v3.bundles.buttons-bundle';
import { getPropertyForPath } from '../../utils';

interface Props {
  id: number,
  idPath?: string,
  fieldKey: string;
  relSchema?: string;

  as?: string;

  disableLink?: boolean;
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
    const prop = getPropertyForPath(props.fieldKey);
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

  if (props.as === 'select' || props.disableLink) {
    return <String value={value} />
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
