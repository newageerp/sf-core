import React, { Fragment } from 'react'
import { useTemplateLoader } from '../templates/TemplateLoader';

type Props = {
    totals: any,
}

export default function ListDataTotals(props: Props) {
    const { data: tData } = useTemplateLoader();
    if (!tData.dataTotals) {
        return <Fragment />
    }
    return (
        <Fragment>
            {props.totals.map((total: any) => {
                return (
                    <div className="flex items-center justify-end">
                        <label className="text-sm font-semibold text-right w-36 p-2 bg-white">{total.title}</label>
                        {!!tData.dataTotals && (total.field in tData.dataTotals) &&
                            <span className="w-36 text-right text-sm p-2 bg-white">{tData.dataTotals[total.field].toFixed(2)}</span>
                        }
                    </div>
                )
            })}
        </Fragment>
    )
}
