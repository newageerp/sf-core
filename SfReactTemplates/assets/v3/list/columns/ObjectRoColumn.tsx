import React, { Fragment } from "react";
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { String, Int, Float } from "@newageerp/data.table.base";
import { RsButton } from "@newageerp/v3.bundles.buttons-bundle";
import { StatusWidget, StatusWidgetColors } from "@newageerp/v3.bundles.widgets-bundle";
import { NaeSStatuses } from "../../../_custom/config/NaeSStatuses";

interface Props {
  fieldKey: string;
  idKey: string;
  relSchema: string;

  as?: string;

  hasLink?: undefined | ("main" | "modal" | "new");

  fieldType: string,
}

export default function ObjectRoColumn(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  let value: any = "";
  try {
    value = props.fieldKey.split('.').reduce((previous, current) => previous[current], element);
  } catch (e) {

  }
  let elementId = 0;
  try {
    elementId = props.idKey.split('.').reduce((previous, current) => previous[current], element);
  } catch (e) {

  }

  if (!props.hasLink) {
    if (props.fieldType === 'status') {
      const k = props.fieldKey.split('.');

      const activeStatus = NaeSStatuses.filter(
        (s) =>
          s.type === k[k.length-1] &&
          s.schema === props.relSchema &&
          s.status === value
      );
      // @ts-ignore
      const statusVariant: keyof typeof StatusWidgetColors =
        activeStatus.length > 0 && !!activeStatus[0].variant
          ? activeStatus[0].variant
          : "blue";
      const statusText = activeStatus.length > 0 ? activeStatus[0].text : "";
      
      
      return <StatusWidget
        isCompact={false}
        title={statusText}
        className="tw3-max-w-[120px] tw3-truncate"
        color={statusVariant}
      >
        {statusText}
      </StatusWidget>;
    }
    if (props.fieldType === 'float') {
      return <Float value={value} />;
    }
    if (props.fieldType === 'number') {
      return <Int value={value} />;
    }
    return <String value={value} />;
  }

  if (!elementId) {
    return <Fragment />;
  }

  return (
    <RsButton
      defaultClick={props.hasLink}
      id={elementId}
      schema={props.relSchema}
      button={{
        children: <String value={value} />,
        color: "white",
        skipPadding: true,
      }}
    />
  );
}
