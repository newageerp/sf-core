import React from "react";
import { NaeRecordProvider } from "../old-ui/OldNaeRecord";
import { Template, TemplatesParser } from "../templates/TemplateLoader";

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
    <NaeRecordProvider
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
    </NaeRecordProvider>
  );
}
