import React from 'react'
import TemplateLoader from '../../templates/TemplateLoader';
import { NaeRecordProvider } from '../OldNaeRecord';
import { useBuilderWidget } from './OldBuilderWidgetProvider';


interface Props {
    schema: string,
    viewType: string,
    compactView?: boolean,
    contentClassName?: string,
}

export default function ViewCompactBuilderWidget(props: Props) {
    const element = useBuilderWidget().element;

    return (
        <NaeRecordProvider
            schema={props.schema}
            viewType={props.viewType}
            id={element.id}
            viewId={"ViewCompactBuilderWidget-" + element.id + "-" + props.schema}
            showOnEmpty={true}
        >
            <TemplateLoader
                templateName='InlineViewContent'
                data={{
                    schema: props.schema,
                    type: props.viewType,
                    id: element.id,
                    isCompact: props.compactView,
                }}
            />

            {/* <ViewCompact
                schema={props.schema}
                type={props.viewType}
                id={element.id}
                options={{
                    compactView: props.compactView,
                    contentClassName: props.contentClassName,
                }}
            /> */}
        </NaeRecordProvider>
    )
}
