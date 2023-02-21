import React from 'react'
import { NotesContent as NotesContentTpl } from "@newageerp/v3.bundles.notes-bundle";

interface Props {
    showOnlyMy?: boolean;
    schema: string,
    id: number,
    options?: any
}

export default function NotesContent(props: Props) {
    return (
        <NotesContentTpl
            showOnlyMy={props.showOnlyMy}
            schema={props.schema}
            id={props.id}
            options={props.options}
        />
    )
}
