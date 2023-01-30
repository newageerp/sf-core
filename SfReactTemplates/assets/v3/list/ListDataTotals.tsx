import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { useTheme } from '@newageerp/v3.bundles.layout-bundle';
import classNames from 'classnames';

type Props = {
    totals: any,
}

export default function ListDataTotals(props: Props) {
    const { data: tData } = useTemplatesLoader();
    const {theme} = useTheme();

    if (!tData.dataTotals) {
        return <Fragment />
    }
    return (
        <div className='tw3-flex tw3-justify-end'>
        <div className={
            classNames(
                'tw3-mt-10',
                'tw3-p-6',
                theme['bg-white']
            )
        }>
            {props.totals.map((total: any) => {
                return (
                    <div className="tw3-flex tw3-items-center tw3-justify-end">
                        <label className="tw3-text-sm tw3-font-semibold tw3-text-right tw3-w-36 tw3-p-2">{total.title}</label>
                        {!!tData.dataTotals && (total.field in tData.dataTotals) &&
                            <span className="tw3-w-36 tw3-text-right tw3-text-sm tw3-p-2">{tData.dataTotals[total.field].toFixed(2)}</span>
                        }
                    </div>
                )
            })}
        </div>
        </div>
    )
}
