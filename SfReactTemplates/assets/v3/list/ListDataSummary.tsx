import React, { useEffect, useState } from 'react'
import { axiosInstance } from '../api/config';
import { useTemplateLoader } from '../templates/TemplateLoader';
import { Table, Th, Td } from '@newageerp/ui.table.base.table'
import { Float } from '@newageerp/data.table.base';

type ISummary = {
    title: string;
    field: string;
    type: string;
    groupBy: string;
}

type Props = {
    summary: ISummary[],
    schema: string,
}

export default function ListDataSummary(props: Props) {
    const { data: tData } = useTemplateLoader();
    const [data, setData] = useState<any>([]);

    const getData = () => {
        axiosInstance.post(
            `/app/nae-core/u/groupedList/${props.schema}`,
            {
                filters: tData.filter.prepareFilter(),
                summary: props.summary,
            }
        ).then(res => setData(res.data));
    }

    useEffect(() => {
        getData();
    }, [props.schema, props.summary])

    const groupKeys = Object.keys(data);

    return (
        <div className='tw3-space-y-4'>
            {groupKeys.map((groupField: string) => {
                const summaryFields = props.summary.filter(f => f.groupBy === groupField);
                const rows = Object.keys(data[groupField]);

                return (
                    <Table
                        key={`table-${groupField}`}
                        thead={
                            <thead>
                                <tr>
                                    <Th></Th>
                                    {summaryFields.map((t) => <Th key={`th-${groupField}-${t.field}`} textAlignment={"tw3-text-right"}>{t.title}</Th>)}
                                </tr>
                            </thead>
                        }
                        tbody={
                            <tbody>
                                {rows.map((groupF: string) => {
                                    return (
                                        <tr key={`row-${groupField}-${groupF}`}>
                                            <Td>{groupF}</Td>
                                            {summaryFields.map((t) => <Td key={`th-${groupField}-${t.field}-${groupF}`} textAlignment={"tw3-text-right"}>
                                                <Float value={data[groupField][groupF][t.field]} />
                                            </Td>)}
                                        </tr>
                                    )
                                })}
                            </tbody>
                        }
                    />
                )
            })}
        </div>
    )
}
