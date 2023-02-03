import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle'
import React, { Fragment } from 'react'
import { useTranslation } from 'react-i18next';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { useListDataSource } from '../ListDataSource';

export default function AddSelectButton() {
    const { t } = useTranslation();
    const { data: tData } = useTemplatesLoader();
    const { element } = tData;
    const { data } = useListDataSource();

    if (data.selection.items.indexOf(element.id) >= 0) {
        return <Fragment />
    }

    return (
        <ToolbarButton
            iconName='arrow-pointer'
            onClick={() => data.selection.addElement(element)}
        >
            {t('Select')}
        </ToolbarButton>
    )
}
