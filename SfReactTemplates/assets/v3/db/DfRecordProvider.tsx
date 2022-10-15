import React from 'react'

interface Props {
    paths: string[],
    id: number
}

export default function DfRecordProvider(props: Props) {
    return (
        <div className={'tw3-mb-10'}>
            <div>DfRecordProvider</div>
            <div>
                {JSON.stringify(props.paths)}
            </div>
        </div>
    )
}
