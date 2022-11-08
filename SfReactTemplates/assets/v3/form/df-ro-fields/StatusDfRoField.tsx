import { Base } from '@newageerp/v2.element.status-badge.base';
import React from 'react'
import { NaeSStatuses } from '../../../_custom/config/NaeSStatuses';
import { useDfValue } from '../../hooks/useDfValue';

interface Props {
  id: number;
  fieldKey: string;
  schema: string
}

export default function StatusDfRoField(props: Props) {
  const elementStatus = useDfValue({ id: props.id, path: props.fieldKey });

  const activeStatus = NaeSStatuses.filter(s => s.type === props.fieldKey && s.schema === props.schema && s.status === elementStatus);
  const statusVariant = activeStatus.length > 0 && !!activeStatus[0].variant ? activeStatus[0].variant : 'blue';
  const statusText = activeStatus.length > 0 ? activeStatus[0].text : '';

  return (
    <Base variant={statusVariant}>
      {statusText}
    </Base>
  )
}
