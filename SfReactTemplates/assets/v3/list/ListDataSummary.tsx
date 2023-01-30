import React, { useEffect, useState } from "react";
import { axiosInstance } from "@newageerp/v3.bundles.utils-bundle";
import { useTemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { Table, Th, Td } from "@newageerp/v3.bundles.layout-bundle";
import { Float, Int } from "@newageerp/data.table.base";
import { getPropertyForPath } from "../utils";
import { Base } from "@newageerp/v2.element.status-badge.base";
import { NaeSStatuses } from "../../_custom/config/NaeSStatuses";
import { LogoLoader } from "@newageerp/ui.ui-bundle";
import { useTranslation } from 'react-i18next'
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle'

export type ISummary = {
  title: string;
  field: string;
  type: string;
  groupBy: string;
};

type Props = {
  summary: ISummary[];
  schema: string;
};

const uri = 'data:application/vnd.ms-excel;base64,';

const template =
  '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-mic' +
  'rosoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta cha' +
  'rset="UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:Exce' +
  'lWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/>' +
  '</x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></' +
  'xml><![endif]--></head><body>{table}</body></html>';

const base64 = (s: string) => {
  return window.btoa(unescape(encodeURIComponent(s)));
}

const doFormat = (s: string, tableId: string) => {
  const context = {
    worksheet: 'Worksheet',
    // @ts-ignore
    table: window.document.getElementById(tableId).outerHTML,
  };

  // @ts-ignore
  return s.replace(/{(\w+)}/g, (_m, p) => context[p]);
}

const doDownload = (tableId: string) => {
  const element = window.document.createElement('a');
  element.href = uri + base64(doFormat(template, tableId));
  element.download = 'export.xls';
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
}

export default function ListDataSummary(props: Props) {
  const { t } = useTranslation()
  
  const [isLoading, setIsLoading] = useState(false);
  const { data: tData } = useTemplatesLoader();
  const [data, setData] = useState<any>([]);
  const [totalData, setTotalData] = useState<any>([]);
  const [dataSummaryFields, setDataSummaryFields] = useState<ISummary[]>([]);

  const getData = () => {
    setIsLoading(true);
    axiosInstance
      .post(`/app/nae-core/u/groupedList/${props.schema}`, {
        filters: tData.filter.prepareFilter(),
        summary: props.summary,
        sort: tData.sort,
      })
      .then((res) => {
        setData(res.data.data);
        setTotalData(res.data.total);
        setDataSummaryFields(res.data.summaryFields);
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
        const summaryFields = dataSummaryFields.filter(
          (f) => f.groupBy === groupField
        );
        const rows = Object.keys(data[groupField]);

        const tId = `table-${groupField}`;

        return (
          <Table
            key={tId}
            id={tId}
            thead={
              <thead>
                <tr>
                  <Th>
                    <ToolbarButton
                      iconName={"file-excel"}
                      onClick={() => doDownload(tId)}
                    />
                  </Th>
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
                          {t.type === 'count' ? <Int value={data[groupField][groupF][t.field]} /> : <Float value={data[groupField][groupF][t.field]} />}

                        </Td>
                      ))}
                    </tr>
                  );
                })}
                <tr className="total-row tw3-font-medium">
                  <Td>{`${t('Total')}`}</Td>
                  {summaryFields.map((t) => (
                    <Td
                      key={`th-${groupField}-${t.field}`}
                      textAlignment={"tw3-text-right"}
                    >
                      {t.type === 'count' ? <Int value={totalData[groupField][t.field]} /> : <Float value={totalData[groupField][t.field]} />}
                    </Td>
                  ))}
                </tr>
              </tbody>
            }
          />
        );
      })}
    </div>
  );
}
