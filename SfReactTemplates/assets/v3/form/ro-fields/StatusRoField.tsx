import { Base } from '@newageerp/v2.element.status-badge.base';
import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { NaeSStatuses } from '../../../_custom/config/NaeSStatuses';

interface Props {
  fieldKey: string;
  schema: string
}

export default function StatusRoField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element } = tData;

  if (!element) {
    return <Fragment />;
  }

  const elementStatus = element[props.fieldKey];
  const activeStatus = NaeSStatuses.filter(s => s.type === props.fieldKey && s.schema === props.schema && s.status === elementStatus);
  const statusVariant = activeStatus.length > 0 && !!activeStatus[0].variant ? activeStatus[0].variant : 'blue';
  const statusText = activeStatus.length > 0 ? activeStatus[0].text : '';

  return (
    <Base variant={statusVariant}>
      {statusText}
    </Base>
  )

}
