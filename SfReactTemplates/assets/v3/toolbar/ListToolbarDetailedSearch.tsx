import React, { useEffect } from 'react'
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle'
import { useTranslation } from 'react-i18next';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { axiosInstance } from '@newageerp/v3.bundles.utils-bundle';

interface Props {
    schema: string,

}

export default function ListToolbarDetailedSearch(props: Props) {
    const { t } = useTranslation();
    const { data: tData } = useTemplatesLoader();
    const { extendedSearch } = tData;

    useEffect(() => {
        if (extendedSearch.value && extendedSearch.properties.value.length === 0) {
            axiosInstance
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
            {t('Advanced filter')}
        </ToolbarButton>
    )
}
