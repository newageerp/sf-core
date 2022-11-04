import { functions } from '@newageerp/nae-react-ui'
import { TransformViewValueOptions } from '@newageerp/nae-react-ui/dist/functions'
import { INaeFormViewRow, INaeViewField, INaeViewSettings } from '@newageerp/nae-react-ui/dist/interfaces'
import React, { Fragment } from 'react'
import { getPropertyDataForSchema } from '../utils'


interface Props {
    schema: string,
    element: any,
    viewFields: INaeViewSettings,
    options?: TransformViewValueOptions,
}

export default function ViewFieldsComponent(props: Props) {
    return (
        <Fragment>
            {props.viewFields.fields.map(
                (fieldRow: INaeFormViewRow, fRowIndex: number) => {
                    const fRowKey =
                        'view-field-k-' +
                        props.element.id +
                        '-' +
                        props.schema +
                        '-' +
                        fRowIndex
                    return (
                        <div
                            className={'flex space-x-2 hover:bg-nsecondary-50'}
                            key={fRowKey}
                        >
                            {fieldRow.map((f: INaeViewField, colIndex: number) => {
                                const property = f.key
                                    ? getPropertyDataForSchema(props.schema, f.key)
                                    : null
                                return (
                                    <Fragment key={fRowKey + '-' + colIndex}>
                                        {functions.viewTransform.transformViewValue(
                                            f,
                                            props.element,
                                            property,
                                            undefined,
                                            props.options
                                        )}
                                    </Fragment>
                                )
                            })}
                        </div>
                    )
                }
            )}
        </Fragment>
    )
}
