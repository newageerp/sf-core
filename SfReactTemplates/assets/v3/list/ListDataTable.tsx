import { Table } from "@newageerp/ui.ui-bundle";
import React from "react";
import {
  Template,
  TemplatesLoader,
  TemplatesParser,
  useTemplatesLoader,
} from "@newageerp/v3.templates.templates-core";

interface Props {
  header: Template[];
  row: Template[];
  className?: string;
}

export default function ListDataTable(props: Props) {
  const { data: tData } = useTemplatesLoader();
  return (
    <Table
      className={props.className}
      thead={
        <thead>
          <TemplatesParser templates={props.header} />
        </thead>
      }
      tbody={
        <tbody>
          {tData.dataToRender.map((item: any) => {
            return (
              <TemplatesLoader
                key={`item-${item.id}`}
                templates={props.row}
                templateData={{
                  element: item,
                  onAddSelectButton: tData.onAddSelectButton,
                }}
              />
            );
          })}
        </tbody>
      }
    />
  );
}
