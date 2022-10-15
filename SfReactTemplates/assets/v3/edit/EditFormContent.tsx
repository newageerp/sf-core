import { WideToolbar } from '@newageerp/ui.form.base.form-pack';
import classNames from 'classnames';
import React, { Fragment } from 'react'
import TemplateLoader, { Template, useTemplateLoader } from '../templates/TemplateLoader'

interface Props {
    parentElement: any,
    isCompact: boolean,
    schema: string,
    type: string,
    children: Template[]
}

export default function EditFormContent(props: Props) {
    const { data: tData } = useTemplateLoader();

    const templateData = {
        ...tData,
        parentElement: props.parentElement
    }

    return (
        <div className={classNames('tw3-space-y-4', { 'tw3-pb-44': !props.isCompact })}>
            <div className={'tw3-space-y-2'}>
                <TemplateLoader
                    templates={props.children}
                    templateData={templateData}
                />
            </div>
            <WideToolbar
                onSave={tData.onSave}
                onCancel={tData.onBack}
                onExtraSave={tData.onExtraSave}
                size={props.isCompact ? 'base' : 'xl'}
            />
        </div>
    )
}
