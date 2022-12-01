import React, { Fragment } from 'react'
import { useTemplateLoader } from '../templates/TemplateLoader'
import { AlertWidget, transformErrorAlert } from "@newageerp/v3.bundles.widgets-bundle";

export default function FormError() {
    const { data: tData } = useTemplateLoader();

    if (!tData.formDataError) {
        return <Fragment />
    }

    return (
        <AlertWidget color='danger'>
            {transformErrorAlert(tData.formDataError)}
        </AlertWidget>
    )
}
