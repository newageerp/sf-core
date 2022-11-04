import React from 'react';
import { useNaePopup } from '../OldPopupProvider';
import { useBuilderWidget } from './OldBuilderWidgetProvider';
import ButtonUIBuilder, { ButtonUIBuilderProps } from './OldButtonUIBuilder';
import { SFSOpenEditModalWindowProps } from '@newageerp/v3.popups.mvc-popup';
import { useHistory } from 'react-router';
import { getSchemaTitle } from '../../utils';

interface Props {
    button: ButtonUIBuilderProps,
    schema: string,
    inPopup: boolean,
    customTitle?: string,
    createOptions?: string,
    type?: string
}


export default function CreateWidgetBuilder(props: Props) {
    const history = useHistory();

    const parentElement = useBuilderWidget().element;
    const parentSchema = useBuilderWidget().schema;

    const { isPopup } = useNaePopup();
    // const { showEditPopup } = useNaeWindow();

    const createOptions: any = props.createOptions
        ? props.createOptions.replaceAll('@context.id', parentElement ? parentElement.id : -1).replaceAll('@context.schema', parentSchema ? parentSchema : '')
        : {};
    createOptions.convert = {
        schema: parentSchema,
        element: { id: parentElement.id },
    };

    const schemaType = props.type ? props.type : "main";

    const openLink = () => {
        if (isPopup || props.inPopup) {
            SFSOpenEditModalWindowProps({
                id: "new",
                schema: props.schema,
                options: { createOptions: createOptions },
                type: schemaType,
            });
        } else {
            history.push(
                "/u/" + props.schema + "/" + schemaType + "/edit/new",
                { createOptions: createOptions }
            )
        }
    }

    const title = props.customTitle ? props.customTitle : getSchemaTitle(props.schema, false);

    return (
        <ButtonUIBuilder {...props.button} onClick={openLink} children={title} />
    );
}
