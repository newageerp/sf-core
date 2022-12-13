import { MainButton } from '@newageerp/v3.bundles.buttons-bundle'
import React, { Fragment, useState } from 'react'
import { useTranslation } from 'react-i18next'
import { editPopupBySchemaAndType } from '../../../../../editforms/EditPopup'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core'

type Props = {
    schema: string,
}

export default function AddButton(props: Props) {
    const {data: tData} = useTemplatesLoader();

    const [createNew, setCreateNew] = useState(false)
    const toggleCreateNew = () => setCreateNew(!createNew)

    const { t } = useTranslation();

    return (
        <Fragment>
            <MainButton iconName='plus' onClick={toggleCreateNew}>
                {t('Add')}
            </MainButton>
            {!!createNew &&
                <Fragment>
                    {editPopupBySchemaAndType(props.schema, 'main', {
                        onClose: toggleCreateNew,
                        editProps: {
                            schema: props.schema,
                            id: "new",
                            onSaveCallback: (_el, back) => {
                                tData.addElement(_el, back);
                                toggleCreateNew();
                            },
                            parentElement: tData.parentElement,
                            type: 'main'
                        },
                    })}
                </Fragment>
            }
        </Fragment>
    )
}
