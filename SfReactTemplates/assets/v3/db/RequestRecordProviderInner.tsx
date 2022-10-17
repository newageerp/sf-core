import { UI } from "@newageerp/nae-react-ui";
import React from "react";
import TemplateLoader, { Template } from "../templates/TemplateLoader";

interface Props {
  children: Template[];
}

export default function RequestRecordProviderInner(props: Props) {
  const { element } = UI.Record.useNaeRecord();

  return (
    <TemplateLoader
      templates={props.children}
      templateData={{ element: element }}
    />
  );
}
