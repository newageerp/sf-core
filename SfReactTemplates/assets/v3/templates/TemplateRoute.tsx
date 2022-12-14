import React from 'react'
import { useHistory, useParams } from '@newageerp/v3.templates.templates-core';
import {TemplatesLoader} from '@newageerp/v3.templates.templates-core';

export default function TemplateRoute() {
    // const history = useHistory();
    const routeParams = useParams<any>();

    return <TemplatesLoader
        key={JSON.stringify(routeParams)}
        templateName={routeParams.templateName}
        data={routeParams}

    />;
}
