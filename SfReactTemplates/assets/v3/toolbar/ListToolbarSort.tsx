import React, { useEffect, useState } from 'react'
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle'
import { useTranslation } from 'react-i18next'
import { useComponentVisible } from '@newageerp/v3.bundles.hooks-bundle';
import {
    SortController,
    SortingItemOption,
} from '@newageerp/ui.ui-bundle';

import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { axiosInstance } from '@newageerp/v3.bundles.utils-bundle';

interface Props {
    schema: string,
    defaultSort: any[]
}

export default function ListToolbarSort(props: Props) {
    const { data: tData } = useTemplatesLoader();
    const { sort } = tData;

    const { t } = useTranslation();
    const { ref, isComponentVisible, setIsComponentVisible } = useComponentVisible(false);

    const toggleShowSort = () => setIsComponentVisible(!isComponentVisible);

    const [sortOptions, setSortOptions] = useState<SortingItemOption[]>([]);

    const onSortClear = () => {
        sort.onChange(props.defaultSort);
    }

    useEffect(() => {
        if (isComponentVisible && sortOptions.length === 0) {
            axiosInstance
                .post(`/app/nae-core/config-properties/for-sort`, {
                    schema: props.schema,
                })
                .then((response) => {
                    if (response.status === 200) {
                        setSortOptions(response.data.data);
                    }
                });
        }
    }, [isComponentVisible, sortOptions]);

    return (
        <div className="tw3-relative">
            <ToolbarButton
                iconName='arrow-up-small-big'
                onClick={toggleShowSort}
            >
                {t('Sort')}
            </ToolbarButton>

            {isComponentVisible && (
                <div className="tw3-absolute tw3-z-10 tw3-right-0" ref={ref}>
                    {!!sortOptions && sortOptions.length > 0 &&
                        <SortController
                            options={sortOptions}
                            sortingItems={sort.value}
                            setSortingItems={sort.onChange}
                            onClear={onSortClear}
                        />
                    }
                </div>
            )}
        </div>
    )
}
