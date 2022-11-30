import React, { Fragment } from 'react'
import TemplateLoader, { Template, useTemplateLoader } from '../templates/TemplateLoader'
import { filterScopes } from '../utils';
import { useRecoilValue } from 'recoil';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';

interface Props {
    isCompact: boolean,
    schema: string,
    type: string,
    children: Template[],
    scopes?: string[],
}

export default function ViewFormContent(props: Props) {
    const { data: tData } = useTemplateLoader();
    
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
        <TemplateLoader
            templates={props.children}
            templateData={templateData}
        />
    )
}
