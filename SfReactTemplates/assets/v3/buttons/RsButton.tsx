import React from "react";
import { Template, TemplatesParser } from "../templates/TemplateLoader";
import { RsButton as RsButtonTpl } from "@newageerp/v3.buttons.rs-button";

interface Props {
  schema: string;
  elementId: number;
  children: Template[];
  defaultClick: "main" | "modal" | "new";
}

export default function RsButton(props: Props) {
  return (
    <RsButtonTpl
      defaultClick={props.defaultClick}
      id={props.elementId}
      schema={props.schema}
      button={{
        children: <TemplatesParser templates={props.children} />,
        color: "white",
        skipPadding: true,
      }}
    />
  );
}
