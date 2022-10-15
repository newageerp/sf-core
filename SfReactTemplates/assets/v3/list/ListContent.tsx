import React, { Fragment } from 'react'
import { Template, TemplatesParser } from '../templates/TemplateLoader';

interface Props {
    schema: string,
    type: string,
    children: Template[]
}

export default function ListContent(props: Props) {
    // const Comp = tablesDataSourceBySchemaAndType(props.schema, props.type);

    // if (!Comp) {
    //     return <Fragment />
    // }

    return (
        <div className='tw3-space-y-4'>
            <TemplatesParser templates={props.children} />
        </div>
    )
}
