import React, { Fragment } from 'react'
import { MainButton } from '@newageerp/v3.bundles.buttons-bundle';
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
        <Fragment>
            <MainButton
                onClick={onClick}
                iconName='plus'
                color='teal'
                className='tw3-whitespace-nowrap'
            >
                <span className='tw3-hidden md:tw3-block'>
                    {t('New record')}
                </span>
            </MainButton>
        </Fragment>
    )
}
