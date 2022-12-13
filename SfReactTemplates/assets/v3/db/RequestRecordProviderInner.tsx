import React from "react";
import { useNaeRecord } from "../old-ui/OldNaeRecord";
import {TemplatesLoader, Template } from '@newageerp/v3.templates.templates-core';

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
