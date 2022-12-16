import React from 'react'
import { useParams } from '@newageerp/v3.templates.templates-core';
import {TemplatesLoader} from '@newageerp/v3.templates.templates-core';


interface ParamTypes {
    schema: string;
    type: string;
}

interface Props {
    isPopup?: boolean,

    schema?: string,
    type?: string,

    onBack?: () => void,
}

export default function MainList(props: Props) {
    const routeParams = useParams<ParamTypes>();

    const commonProps = { ...routeParams, ...props };

    return (
        <TemplatesLoader
            key={`${commonProps.schema}-${commonProps.type}`}
            templateName="PageMainList"
            data={commonProps}
            templateData={{
                onBack: () => {
                    if (props.onBack) {
                        props.onBack();
                    }
                },
            }}
        />
    )
}
