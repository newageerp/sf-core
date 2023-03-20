import React from "react";
import {TemplatesLoader, Template } from '@newageerp/v3.templates.templates-core';
import { useNaeRecord } from "@newageerp/v3.app.mvc.record-provider";

interface Props {
  children: Template[];
}

export default function RequestRecordProviderInner(props: Props) {
  const { element } = useNaeRecord();

  return (
    <TemplatesLoader
      templates={props.children}
      templateData={{ element: element }}
    />
  );
}
