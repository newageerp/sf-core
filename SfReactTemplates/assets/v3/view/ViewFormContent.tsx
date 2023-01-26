import React, { Fragment } from 'react'
import {TemplatesLoader, Template, useTemplatesLoader } from '@newageerp/v3.templates.templates-core'
import { filterScopes } from '../utils';
import { useTemplatesCore } from '@newageerp/v3.templates.templates-core';

interface Props {
    isCompact: boolean,
    schema: string,
    type: string,
    children: Template[],
    scopes?: string[],
}

export default function ViewFormContent(props: Props) {
    const { data: tData } = useTemplatesLoader();
    
    const {userState} = useTemplatesCore()
    
    const templateData = {
        ...tData,
    }

    const isShow = filterScopes(
        tData.element,
        userState,
        props.scopes
    );

    if (!isShow) {
        return <Fragment/>
    }


    return (
        <TemplatesLoader
            templates={props.children}
            templateData={templateData}
        />
    )
}
