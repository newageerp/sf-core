import React, { Fragment } from "react";
import { useDfValue } from "../../hooks/useDfValue";
import { String } from "@newageerp/data.table.base";

interface Props {
  id: number;
  fieldKey: string;

  stringInline?: boolean;
}

export default function StringDfRoField(props: Props) {
  const value = useDfValue({ id: props.id, path: props.fieldKey });

  if (props.stringInline) {
    return <Fragment>{value}</Fragment>;
  }

  return <String value={value} />;
}
