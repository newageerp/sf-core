import React from 'react'
import { FieldSelect } from '@newageerp/v3.bundles.form-bundle'

interface Props {
    schema: string,
    type: string,
    tabs: any[],
}

export default function ListToolbarTabsSwitch(props: Props) {
    return (
        <FieldSelect
            value={props.type}
            onChange={(type: string) => {
                const event = new CustomEvent(
                    'SFSOpenListWindow',
                    {
                        detail: {
                            schema: props.schema,
                            type: type,
                        }
                    }
                );
                window.dispatchEvent(event);
            }}
            options={props.tabs}
            className="tw3-w-full tw3-max-w-[500px]"
            icon='table-layout'
        />
    )
}
