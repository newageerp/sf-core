import React from 'react'
import { useParams } from 'react-router-dom';
import TemplateLoader from '../templates/TemplateLoader';


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
        <TemplateLoader
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
