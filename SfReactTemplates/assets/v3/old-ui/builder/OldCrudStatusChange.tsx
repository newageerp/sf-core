import React, { Fragment } from 'react'
import { useTranslation } from 'react-i18next';

import Button from '../OldButton';
import PopoverConfirm from '../OldPopoverConfirm';
import { useBuilderWidget } from './OldBuilderWidgetProvider';
import { AlertWidget, transformErrorAlert } from '@newageerp/v3.widgets.alert-widget';
import { INaeStatus } from '../../utils';
import { NaeSStatuses } from '../../../_custom/config/NaeSStatuses';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';

interface Props {
    schema: string,
    type: string,
}

export default function OldCrudStatusChange(props: Props) {
    const [reloadingStatus, setReloadingStatus] = React.useState(0);

    const { element } = useBuilderWidget();
    const { t } = useTranslation();

    const elementStatus = element.status ? element.status : 0;

    const statuses = NaeSStatuses.filter(s => s.type === props.type && s.schema === props.schema);

    const canStatus = (status: number) => {
        const scopes = element.scopes ? element.scopes : [];
        const checkScope = `cant-status-${status}`;
        if (scopes.indexOf(checkScope) > -1) {
            return false;
        }
        return elementStatus !== status;
    }

    const [saveData, saveDataParams] = OpenApi.useUSave(
        props.schema
    );
    const doSave = (data: any) => {
        setReloadingStatus(data.status);
        if (saveDataParams.loading) return;
        saveData(data, element.id);
    };

    return (<Fragment>
        {!!saveDataParams.error &&
            <AlertWidget color='danger'>
                {transformErrorAlert(saveDataParams.error)}
            </AlertWidget>
        }
        {statuses
            .filter((status: INaeStatus) => canStatus(status.status))
            .map((status: INaeStatus) => {
                let bgColor = status.bgColor;
                let brightness = status.brightness;
                let opacity = 100;

                const isLoading =
                    saveDataParams.loading && status.status === reloadingStatus;
                return (
                    <Fragment key={"status-" + status.status}>
                        <PopoverConfirm
                            onClick={() => doSave({ status: status.status })}
                        >
                            <Button
                                icon={"fad fa-arrow-alt-right"}
                                bgColor={bgColor}
                                brightness={brightness}
                                iconLoading={isLoading}
                                opacity={opacity}
                            >
                                {t(status.text)}
                                {isLoading && "..."}
                            </Button>
                        </PopoverConfirm>
                    </Fragment>
                );
            })}
    </Fragment>
    )
}
