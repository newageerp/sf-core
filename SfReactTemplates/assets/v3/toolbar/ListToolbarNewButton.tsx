import React from 'react'
import { MainButton } from '@newageerp/v3.buttons.main-button';
import { useTranslation } from 'react-i18next';
import { useNaePopup } from '../old-ui/OldPopupProvider';

interface Props {
    schema: string,
    type: string,
    forcePopup: boolean,
    options?: any
}

export default function ListToolbarNewButton(props: Props) {
    const { t } = useTranslation();
    const { isPopup } = useNaePopup();
    const openInPopup = isPopup || props.forcePopup;

    const onClick = () => {
        const event = new CustomEvent(
            openInPopup ? 'SFSOpenEditModalWindow' : 'SFSOpenEditWindow',
            {
                detail: {
                    schema: props.schema,
                    id: "new",
                    options: props.options,
                }
            }
        );
        window.dispatchEvent(event);
    }

    return (
        <MainButton
            onClick={onClick}
            iconName='plus'
            color='teal'
            className='tw3-whitespace-nowrap'
        >
            {t('New record')}
        </MainButton>
    )
}
