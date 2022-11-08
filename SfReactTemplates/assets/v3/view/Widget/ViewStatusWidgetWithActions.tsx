import React, { Fragment } from 'react'
import { getHookForSchema } from '../../../_custom/models-cache-data/ModelFields';
import { useTemplateLoader } from '../../templates/TemplateLoader';
import { useTranslation } from 'react-i18next';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { StatusChangeButton } from '@newageerp/v2.element.status-change-button';

interface Props {
    entity: string,
    type: string,

    showOnlyActive?: boolean,
}

export default function ViewStatusWidgetWithActions(props: Props) {
    const { t } = useTranslation();

    const { data: tData } = useTemplateLoader();
    const useHook = getHookForSchema(props.entity);
    const element = useHook(tData.element.id);

    const [doSave] = OpenApi.useUSave(props.entity);
    const changeStatus = (s: number) => {
        doSave({ [props.type]: s }, tData.element.id);
    }

    if (!element) {
        return <Fragment />
    }

    return (
        <StatusChangeButton
            schema={props.entity}
            type={props.type}
            id={tData.element.id}
            onStatusChange={changeStatus}
            reloadState={element.status}
            showOnlyActive={props.showOnlyActive}
        />
    )
}
