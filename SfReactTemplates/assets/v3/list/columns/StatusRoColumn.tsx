import React, { Fragment } from "react";
import { NaeSStatuses } from "../../../_custom/config/NaeSStatuses";
import { useTemplateLoader } from "../../templates/TemplateLoader";
import { StatusWidget, StatusWidgetColors } from '@newageerp/v3.widgets.status-widget';


interface Props {
  fieldKey: string;
  schema: string;
  isSmall: boolean;
}

export default function StatusRoColumn(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const elementStatus = element[props.fieldKey];
  const activeStatus = NaeSStatuses.filter(
    (s) =>
      s.type === props.fieldKey &&
      s.schema === props.schema &&
      s.status === elementStatus
  );
  // @ts-ignore
  const statusVariant: keyof typeof StatusWidgetColors =
    activeStatus.length > 0 && !!activeStatus[0].variant
      ? activeStatus[0].variant
      : "blue";
  const statusText = activeStatus.length > 0 ? activeStatus[0].text : "";

  return <StatusWidget
    isCompact={props.isSmall}
    title={statusText}
    className="tw3-max-w-[120px] tw3-truncate"
    color={statusVariant}
  >
    {statusText}
  </StatusWidget>;
}
