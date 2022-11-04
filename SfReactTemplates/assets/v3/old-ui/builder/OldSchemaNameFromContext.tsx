import React, { Fragment } from 'react'
import { getSchemaTitle } from '../../utils';
import { useNaeSchema } from './OldSchemaProvider';

interface SchemaNameProps {
  plural?: boolean
}

export default function SchemaNameFromContext(props: SchemaNameProps) {
  const { schema } = useNaeSchema();

  if (!schema) {
    return <Fragment>NO SCHEMA DEFINED</Fragment>
  }

  return <Fragment>{getSchemaTitle(schema, !!props.plural)}</Fragment>
}
