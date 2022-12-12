import { Table } from "@newageerp/ui.ui-bundle";
import React from "react";
import TemplateLoader, {
  Template,
  TemplatesParser,
  useTemplateLoader,
} from "../templates/TemplateLoader";

interface Props {
  header: Template[];
  row: Template[];
  className?: string;
}

export default function ListDataTable(props: Props) {
  const { data: tData } = useTemplateLoader();
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
              <TemplateLoader
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
