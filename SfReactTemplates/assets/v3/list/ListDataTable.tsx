import { Table } from "@newageerp/v3.bundles.layout-bundle";
import React, { Fragment } from "react";
import {
  Template,
  TemplatesLoader,
  TemplatesParser,
  useTemplatesLoader,
} from "@newageerp/v3.templates.templates-core";
import { ListTableRow } from "@newageerp/v3.bundles.app-bundle";

interface Props {
  header: Template[];
  row: Template[];
  className?: string;
}

export default function ListDataTable(props: Props) {
  const { data: tData } = useTemplatesLoader();
  return (
    <div className="tw3-space-y-2">
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
                <ListTableRow
                  key={`item-${item.id}`}
                  element={item}
                >
                  <TemplatesLoader

                    templates={props.row}
                    templateData={{
                      element: item,
                      reloadData: tData.reloadData,
                    }}
                  />
                </ListTableRow>
              );
            })}
          </tbody>
        }
      />
      {!!tData.pagingContainer && tData.pagingContainer}
    </div>
  );
}
