import React from 'react'
import { useHistory, useParams } from 'react-router-dom';
import TemplateLoader from './TemplateLoader';

export default function TemplateRoute() {
    // const history = useHistory();
    const routeParams = useParams<any>();

    return <TemplateLoader
        key={JSON.stringify(routeParams)}
        templateName={routeParams.templateName}
        data={routeParams}

    />;
}
