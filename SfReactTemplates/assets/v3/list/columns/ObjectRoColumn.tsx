import React, { Fragment } from "react";
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { String } from "@newageerp/data.table.base";
import { RsButton } from "@newageerp/v3.bundles.buttons-bundle";

interface Props {
  fieldKey: string;
  idKey: string;
  relSchema: string;

  as?: string;

  hasLink?: undefined | ("main" | "modal" | "new");
}

export default function ObjectRoColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  let value = "";
  try {
    value = props.fieldKey.split('.').reduce((previous, current) => previous[current], element);
  } catch (e) {

  }
  let elementId = 0;
  try {
    elementId = props.idKey.split('.').reduce((previous, current) => previous[current], element);
  } catch (e) {

  }

  if (!props.hasLink) {
    return <String value={value} />;
  }

  if (!elementId) {
    return <Fragment />;
  }

  return (
    <RsButton
      defaultClick={props.hasLink}
      id={elementId}
      schema={props.relSchema}
      button={{
        children: <String value={value} />,
        color: "white",
        skipPadding: true,
      }}
    />
  );
}
