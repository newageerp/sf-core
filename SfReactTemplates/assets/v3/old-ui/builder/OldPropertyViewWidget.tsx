import React, { Fragment } from 'react'
import { getHookForSchema } from '../../../_custom/models-cache-data/ModelFields';
import { DfValueView } from '../../hooks/useDfValue';
import { getPropertyForPath } from '../../utils';


interface PropertyLabelProps {
    titlePath: string,
    isCompactView?: boolean,
    className?: string,
}

interface PropertyViewProps {
    id: number,
    property: any,
    label?: PropertyLabelProps,
    contentClassName?: string,
    propertyPath: string,
}

export default function PropertyViewWidget(props: PropertyViewProps) {
    const { id, property } = props;
    const schema = property ? property.schema : '-';

    const useHook = getHookForSchema(schema);

    const rsElement = useHook(id);

    if (!rsElement) {
        return <Fragment />
    }
    const contentClassName: string[] = []
    if (props.contentClassName) {
        contentClassName.push(props.contentClassName);
    }

    if (props.label) {
        const propertyLabel = getPropertyForPath(props.label.titlePath);
        const labelClassName = `${props.label.isCompactView ? "w-full" : "w-32"} text-sm text-nsecondary-400 font-medium ${props.label && props.label.className ? props.label.className : ''}`;
        const contentStyle = { width: props.label.isCompactView ? '100%' : '' };

        return (
            <div className='flex gap-1 items-center flex-wrap'>
                <label className={labelClassName}>{propertyLabel?.title}</label>
                <span
                    className={
                        'flex-grow text-gray-700 text-sm ' + contentClassName.join(' ')
                    }
                    style={contentStyle}
                >
                    <DfValueView path={props.propertyPath} id={props.id} />
                </span>
            </div>
        )
    }

    return (
        <div className={contentClassName.join(' ')}>
            <DfValueView path={props.propertyPath} id={props.id} />
        </div>
    )
}