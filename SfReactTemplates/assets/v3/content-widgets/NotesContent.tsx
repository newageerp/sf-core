import React from 'react'
import NotesContentTpl from '../../apps/notes/NotesContent';

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
