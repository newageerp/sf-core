import React from 'react'
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
            TODO
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
