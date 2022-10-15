import { ToolbarButton } from '@newageerp/v3.buttons.toolbar-button'
import React from 'react'
import { useTranslation } from 'react-i18next';
import { useTemplateLoader } from '../../templates/TemplateLoader';

export default function AddSelectButton() {
    const { t } = useTranslation();
    const { data: tData } = useTemplateLoader();
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
