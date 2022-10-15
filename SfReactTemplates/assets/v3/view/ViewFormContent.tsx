import React, { Fragment } from 'react'
import TemplateLoader, { Template, useTemplateLoader } from '../templates/TemplateLoader'

interface Props {
    isCompact: boolean,
    schema: string,
    type: string,
    children: Template[]
}

export default function ViewFormContent(props: Props) {
    const { data: tData } = useTemplateLoader();

    const templateData = {
        ...tData,
    }

    return (
        <TemplateLoader
            templates={props.children}
            templateData={templateData}
        />
    )
}
