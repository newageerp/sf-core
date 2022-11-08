import React, { useEffect } from 'react'
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle'
import { useTranslation } from 'react-i18next';
import { useTemplateLoader } from '../templates/TemplateLoader';
import axios from 'axios';

interface Props {
    schema: string,

}

export default function ListToolbarDetailedSearch(props: Props) {
    const { t } = useTranslation();
    const { data: tData } = useTemplateLoader();
    const { extendedSearch } = tData;

    useEffect(() => {
        if (extendedSearch.value && extendedSearch.properties.value.length === 0) {
            axios
                .post(`/app/nae-core/config-properties/for-filter`, {
                    schema: props.schema,
                })
                .then((response) => {
                    if (response.status === 200) {
                        extendedSearch.properties.onChange(response.data.data);
                    }
                });
        }
    }, [props.schema, extendedSearch.value, extendedSearch.properties.value]);

    return (
        <ToolbarButton
            iconName='filter'
            onClick={() => extendedSearch.onChange(!extendedSearch.value)}
        >
            {t('Filter')}
        </ToolbarButton>
    )
}
