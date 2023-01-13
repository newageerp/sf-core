import { WideToolbar } from '@newageerp/v3.bundles.form-bundle';
import classNames from 'classnames';
import React, { Fragment } from 'react'
import FormError from '../form/FormError';
import { TemplatesLoader, Template, useTemplatesLoader } from '@newageerp/v3.templates.templates-core'

interface Props {
    parentElement: any,
    isCompact: boolean,
    schema: string,
    type: string,
    children: Template[]
}

export default function EditFormContent(props: Props) {
    const { data: tData } = useTemplatesLoader();

    const templateData = {
        ...tData,
        parentElement: props.parentElement
    }

    return (
        <div className={classNames('tw3-space-y-4', { 'tw3-pb-44': !props.isCompact })}>
            <div className={'tw3-space-y-2'}>
                <TemplatesLoader
                    templates={props.children}
                    templateData={templateData}
                />
            </div>
            <FormError />
            <WideToolbar
                onSave={tData.onSave}
                onCancel={tData.onBack}
                onExtraSave={tData.onExtraSave}
                small={props.isCompact}
            />
        </div>
    )
}
