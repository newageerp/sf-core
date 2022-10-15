import React, { Fragment } from "react";
import { useTemplateLoader } from "../../templates/TemplateLoader";
import { String } from "@newageerp/data.table.string";
import { RsButton } from "@newageerp/v3.buttons.rs-button";

interface Props {
  fieldKey: string;
  idKey: string;
  relSchema: string;

  as?: string;

  hasLink?: undefined | ("main" | "modal" | "new");
}

export default function ObjectRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
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
