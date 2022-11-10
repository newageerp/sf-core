import React from 'react'

type ISummary = {
    title: string;
    field: string;
    type: string;
    groupBy: string;
}

type Props = {
    summary: ISummary[],
}

export default function ListDataSummary(props: Props) {
    return (
        <div>ListDataSummary</div>
    )
}
