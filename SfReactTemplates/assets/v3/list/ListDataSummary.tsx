import React, { useEffect, useState } from "react";
import { axiosInstance } from "../api/config";
import { useTemplateLoader } from "../templates/TemplateLoader";
import { Table, Th, Td } from "@newageerp/ui.ui-bundle";
import { Float } from "@newageerp/data.table.base";
import { getPropertyForPath } from "../utils";
import { Base } from "@newageerp/v2.element.status-badge.base";
import { NaeSStatuses } from "../../_custom/config/NaeSStatuses";
import { LogoLoader } from "@newageerp/ui.ui-bundle";

type ISummary = {
  title: string;
  field: string;
  type: string;
  groupBy: string;
};

type Props = {
  summary: ISummary[];
  schema: string;
};

export default function ListDataSummary(props: Props) {
  const [isLoading, setIsLoading] = useState(false);
  const { data: tData } = useTemplateLoader();
  const [data, setData] = useState<any>([]);

  const getData = () => {
    setIsLoading(true);
    axiosInstance
      .post(`/app/nae-core/u/groupedList/${props.schema}`, {
        filters: tData.filter.prepareFilter(),
        summary: props.summary,
      })
      .then((res) => {
        setData(res.data);
        setIsLoading(false);
      });
  };

  useEffect(() => {
    getData();
  }, [props.schema, props.summary, tData.filter.extraFilter]);

  const groupKeys = Object.keys(data);

  return (
    <div className="tw3-space-y-4">
      {isLoading && <LogoLoader />}
      {groupKeys.map((groupField: string) => {
        const summaryFields = props.summary.filter(
          (f) => f.groupBy === groupField
        );
        const rows = Object.keys(data[groupField]);

        return (
          <Table
            key={`table-${groupField}`}
            thead={
              <thead>
                <tr>
                  <Th></Th>
                  {summaryFields.map((t) => (
                    <Th
                      key={`th-${groupField}-${t.field}`}
                      textAlignment={"tw3-text-right"}
                    >
                      {t.title}
                    </Th>
                  ))}
                </tr>
              </thead>
            }
            tbody={
              <tbody>
                {rows.map((groupF: string) => {
                  let val: any = groupF;
                  const prop = getPropertyForPath(
                    `${props.schema}.${groupField}`
                  );
                  if (prop && prop.naeType === "status") {
                    const activeStatus = NaeSStatuses.filter(
                      (s) =>
                        s.type === groupField &&
                        s.schema === props.schema &&
                        // @ts-ignore
                        s.status == groupF
                    );
                    const statusVariant =
                      activeStatus.length > 0 && !!activeStatus[0].variant
                        ? activeStatus[0].variant
                        : "blue";
                    const statusText =
                      activeStatus.length > 0 ? activeStatus[0].text : "";
                    val = <Base variant={statusVariant}>{statusText}</Base>;
                  }
                  return (
                    <tr key={`row-${groupField}-${groupF}`}>
                      <Td>{val}</Td>
                      {summaryFields.map((t) => (
                        <Td
                          key={`th-${groupField}-${t.field}-${groupF}`}
                          textAlignment={"tw3-text-right"}
                        >
                          <Float value={data[groupField][groupF][t.field]} />
                        </Td>
                      ))}
                    </tr>
                  );
                })}
              </tbody>
            }
          />
        );
      })}
    </div>
  );
}
