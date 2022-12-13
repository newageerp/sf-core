import React, { Fragment } from 'react'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core'
import { AlertWidget, transformErrorAlert } from "@newageerp/v3.bundles.widgets-bundle";

export default function FormError() {
    const { data: tData } = useTemplatesLoader();

    if (!tData.formDataError) {
        return <Fragment />
    }

    return (
        <AlertWidget color='danger' width='tw3-w-full'>
            {transformErrorAlert(tData.formDataError)}
        </AlertWidget>
    )
}
