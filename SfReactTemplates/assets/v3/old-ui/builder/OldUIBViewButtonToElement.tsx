import React, { Fragment } from 'react'
import { getHookForSchema } from '../../../../UserComponents/ModelsCacheData/ModelFields';
import { getPropertyForPath } from '../../utils';
import { useBuilderWidget } from './OldBuilderWidgetProvider';
import { useUIBuilder } from './OldUIBuilderProvider';
import SchemaMultiLink from "../OldButtonSchemaMultiLink"


interface Props {
    path: string,
}

export default function UIBViewButtonToElement(props: Props) {
    const { element } = useBuilderWidget();
    const { defaults } = useUIBuilder();

    const property = getPropertyForPath(props.path);
    const schema = property ? property.schema : '-';

    const useHook = getHookForSchema(schema);

    const keypath = props.path.split('.').splice(1).join(".");
    let elementId = -1;
    try {
        elementId = keypath.split('.').reduce((previous, current) => previous[current], element);
    } catch (e) {

    }

    const rsElement = useHook(elementId);

    if (!rsElement) {
        return <Fragment />
    }

    const defaultsSchema = defaults.filter(d => d.config.schema === schema);
    let elementKey = 'id';
    if (defaultsSchema.length > 0) {
        elementKey = defaultsSchema[0].config.defaultPath.split(".")[1];
    }

    return (
        <SchemaMultiLink
            id={elementId}
            schema={schema}
            className={"w-full text-left rounded-md shadow-sm text-base px-2 py-2 bg-blue-500 text-white"}
            customColor={true}
            showExtraAlways={true}
        >
            {rsElement[elementKey]}
        </SchemaMultiLink>
    )
}
