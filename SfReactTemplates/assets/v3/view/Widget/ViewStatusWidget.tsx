import React, { Fragment } from 'react'
import { getHookForSchema } from '../../../_custom/models-cache-data/ModelFields';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { Base } from '@newageerp/v2.element.status-badge.base';
import { NaeSStatuses } from '../../../_custom/config/NaeSStatuses';
import { useTranslation } from 'react-i18next';

interface Props {
    entity: string,
    type: string,
}

export default function ViewStatusWidget(props: Props) {
    const { t } = useTranslation();

    const { data: tData } = useTemplatesLoader();
    const useHook = getHookForSchema(props.entity);
    const element = useHook(tData.element.id);

    if (!element) {
        return <Fragment />
    }

    const elementStatus = element[props.type];
    const activeStatus = NaeSStatuses.filter(s => s.type === props.type && s.schema === props.entity && s.status === elementStatus);
    const statusVariant = activeStatus.length > 0 && !!activeStatus[0].variant ? activeStatus[0].variant : 'blue';
    const statusText = activeStatus.length > 0 ? activeStatus[0].text : '';

    return (
        <Base variant={statusVariant}>
            {statusText}
        </Base>
    )
}

