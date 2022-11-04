import React from 'react';
import { useBuilderWidget } from './OldBuilderWidgetProvider';
import ButtonUIBuilder, { ButtonUIBuilderProps } from './OldButtonUIBuilder';

interface Props {
    button: ButtonUIBuilderProps,
    link: string,
}

export default function ButtonUIBuilderOpenLink(props: Props) {
    const parentElement = useBuilderWidget().element;

    const openLink = () => {
        // @ts-ignore
        const token : string = localStorage.getItem('token');
        const link = props.link
            .replaceAll('@context.id', parentElement ? parentElement.id : -1)
            .replaceAll('@context.token', token);
        window.open(link, '_blank');
    }

    return (
        <ButtonUIBuilder {...props.button} onClick={openLink} />
    );
}
