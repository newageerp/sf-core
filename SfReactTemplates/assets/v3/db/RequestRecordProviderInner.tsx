import React from "react";
import { useNaeRecord } from "../old-ui/OldNaeRecord";
import TemplateLoader, { Template } from "../templates/TemplateLoader";

interface Props {
  children: Template[];
}

export default function RequestRecordProviderInner(props: Props) {
  const { element } = useNaeRecord();

  return (
    <TemplateLoader
      templates={props.children}
      templateData={{ element: element }}
    />
  );
}
