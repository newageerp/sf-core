import React from "react";
import { Template, TemplatesParser } from '@newageerp/v3.templates.templates-core';
import { RecordProvider } from "@newageerp/v3.app.mvc.record-provider";

interface Props {
  schema: string;
  viewType: string;
  id: string;

  showOnEmpty?: boolean;
  defaultViewIndex?: number;

  children: Template[];
}

export default function RequestRecordProvider(props: Props) {
  return (
    <RecordProvider
      schema={props.schema}
      viewType={props.viewType}
      id={props.id}
      viewId={"MvcViewRoutePageWoLayout-" + props.id + "-" + props.schema}
      showOnEmpty={true}
      defaultViewIndex={
        props.defaultViewIndex ? props.defaultViewIndex : undefined
      }
    >
      <TemplatesParser templates={props.children} />
    </RecordProvider>
  );
}
