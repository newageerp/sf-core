import React from 'react'
import MailsContentTpl from '../../apps/mails/MailsContent';

interface Props {
    schema: string,
    id: number,
}

export default function MailsContent(props: Props) {
    return (
        <MailsContentTpl
            schema={props.schema}
            id={props.id}
        />
    )
}
