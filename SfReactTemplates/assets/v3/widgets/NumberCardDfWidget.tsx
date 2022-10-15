import { NumberCardWidgetColors } from '@newageerp/v3.widgets.number-card-widget';
import React from 'react'
import { NumberCardWidget } from "@newageerp/v3.widgets.number-card-widget";
import { useDfValue } from '../hooks/useDfValue';
import { functions } from '@newageerp/nae-react-ui';

type NumberCardDfWidgetProps = {
    title?: string;
    currency?: string;
    isCompact?: boolean;
    description?: string;
    color?: keyof typeof NumberCardWidgetColors;
    className?: string;
    editForm?: any,

    childrenPath: string,
    elementId: number,
    currencyPath?: string
}

export default function NumberCardDfWidget(props: NumberCardDfWidgetProps) {
    const value = useDfValue({ id: props.elementId, path: props.childrenPath });
    const currency = useDfValue({ id: props.elementId, path: props.currencyPath ? props.currencyPath : '-' })
    const prop = functions.properties.getPropertyForPath(props.childrenPath);
    const asFloat = !!prop && prop.naeType === 'float';

    return (
        <NumberCardWidget {...props} asFloat={asFloat} currency={currency ? currency : props.currency}>
            {value}
        </NumberCardWidget>
    )
}
