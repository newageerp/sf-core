import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle'
import React from 'react'
import { useTranslation } from 'react-i18next';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';

export default function AddSelectButton() {
    const { t } = useTranslation();
    const { data: tData } = useTemplatesLoader();
    const { element } = tData;

    return (
        <ToolbarButton
            iconName='arrow-pointer'
            onClick={() => tData.onAddSelectButton(element)}
        >
            {t('Select')}
        </ToolbarButton>
    )
}
