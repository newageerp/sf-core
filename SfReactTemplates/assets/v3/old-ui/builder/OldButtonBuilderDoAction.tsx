import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { AlertWidget, transformErrorAlert } from '@newageerp/v3.bundles.widgets-bundle';
import React, { Fragment } from 'react';
import { useBuilderWidget } from './OldBuilderWidgetProvider';

import ButtonUIBuilder, { ButtonUIBuilderProps } from './OldButtonUIBuilder';

interface Props {
    button: ButtonUIBuilderProps,
    action: string,
}

export default function ButtonUIBuilderDoAction(props: Props) {
    const parentElement = useBuilderWidget().element;

    const [doReq, doReqParams] = OpenApi.useURequest(props.action);

    const doAction = () => {
        doReq(parentElement)
    }

    let buttonProps = { ...props.button };
    if (buttonProps.icon) {
        buttonProps.icon.rotate = doReqParams.loading;
    }

    return (
        <Fragment>
            <ButtonUIBuilder {...buttonProps} onClick={doAction} />
            {doReqParams.error && <AlertWidget color='danger'>
            {transformErrorAlert(doReqParams.error)}
          </AlertWidget>}
        </Fragment>
    );
}
