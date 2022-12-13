import React, { Fragment } from "react";
import { NaeSStatuses } from "../../../_custom/config/NaeSStatuses";
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { StatusWidget, StatusWidgetColors } from '@newageerp/v3.bundles.widgets-bundle';


interface Props {
  fieldKey: string;
  schema: string;
  isSmall: boolean;
}

export default function StatusRoColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
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
