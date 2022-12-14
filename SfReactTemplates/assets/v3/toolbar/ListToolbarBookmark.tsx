import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle';
import React, { Fragment, useEffect, useState } from 'react'
import { axiosInstance } from '../api/config';
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';

type Props = {
    schema: string,
    user: number,
}

export default function ListToolbarBookmark(props: Props) {
    const _path = `${props.schema}-${props.user}-bookmarks`;

    const { data: tData } = useTemplatesLoader();
    const { onAddExtraFilter, filter } = tData;

    const [data, setData] = useState([]);
    const loadExistingBookmarks = () => {
        const url = '/app/proxy/bookmarks/get-for-user-and-schema';
        axiosInstance
            .post(
                url,
                {
                    data: {
                        sourceSchema: props.schema,
                        user: props.user
                    }
                }
            )
            .then((response) => {
                if (response.status === 200) {
                    // console.log('response.data', response.data);
                    setData(response.data.data.map((el: any) => el.sourceId));
                } else {
                    // UIConfig.toast.error(t('Klaida'))
                }
            });
    };
    useEffect(loadExistingBookmarks, []);

    const isActiveFilter = !!filter?.extraFilter && !!filter.extraFilter[_path];

    const toggleFilter = () => {
        if (!isActiveFilter) {
            onAddExtraFilter(_path, {
                and: [['i.id', 'in', data, true]],
            });
        } else {
            onAddExtraFilter(_path, undefined);
        }
    }

    return (
        <ToolbarButton
            iconName='bookmark'
            bgColor={isActiveFilter ? 'tw3-bg-teal-50' : undefined}
            textColor={isActiveFilter ? 'tw3-text-teal-700' : undefined}
            onClick={toggleFilter} 
            disabled={data.length === 0}
            />
    )
}
