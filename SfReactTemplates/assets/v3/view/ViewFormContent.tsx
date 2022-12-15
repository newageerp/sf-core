import React, { Fragment } from 'react'
import {TemplatesLoader, Template, useTemplatesLoader } from '@newageerp/v3.templates.templates-core'
import { filterScopes } from '../utils';
import { useRecoilValue } from '@newageerp/v3.templates.templates-core';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';

interface Props {
    isCompact: boolean,
    schema: string,
    type: string,
    children: Template[],
    scopes?: string[],
}

export default function ViewFormContent(props: Props) {
    const { data: tData } = useTemplatesLoader();
    
    const userState = useRecoilValue(OpenApi.naeUserState);
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
