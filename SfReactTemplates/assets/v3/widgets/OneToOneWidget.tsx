import React, { Fragment, useState } from 'react'
import { useTranslation } from 'react-i18next';
import { WhiteCard } from '@newageerp/v3.bundles.widgets-bundle'
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle';
import { TextCardTitle } from '@newageerp/v3.bundles.typography-bundle';
import { TemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { getPropertyForPath } from '../utils';
import { useDfValue } from '../hooks/useDfValue';

type Props = {
    path: string,
    id: number,
    contentType: string,
}

export default function OneToOneWidget(props: Props) {
    const property = getPropertyForPath(props.path);
    const dfValue = useDfValue({ path: `${props.path}.id`, id: props.id });
    const { t } = useTranslation();

    const relClass = property && property.format ? property.format : '-';

    const [doRemove, doRemoveParams] = OpenApi.useURemove(relClass)

    const onCreate = () => {
        const event = new CustomEvent(
            'SFSOpenEditModalWindow',
            {
                detail: {
                    schema: relClass,
                    type: props.contentType,
                    id: "new",
                    options: {
                        createOptions: {
                            convert: {
                                schema: props.path.split(".")[0],
                                element: { id: props.id },
                            }
                        }
                    }
                }
            }
        );
        window.dispatchEvent(event);
    }
    const onEdit = () => {
        const event = new CustomEvent(
            'SFSOpenEditModalWindow',
            {
                detail: {
                    schema: relClass,
                    type: props.contentType,
                    id: dfValue,
                }
            }
        );
        window.dispatchEvent(event);
    }
    const onRemove = () => {
        doRemove(dfValue);
    }

    if (!property) {
        return <Fragment />
    }

    return (
        <WhiteCard isCompact={true}>
            <div className='tw3-flex tw3-items-center tw3-gap-2'>
                <TextCardTitle className='tw3-flex-grow'>{t(property.title)}</TextCardTitle>
                {!dfValue && <ToolbarButton iconName='plus' onClick={onCreate} />}
                {!!dfValue && <ToolbarButton iconName='edit' onClick={onEdit} />}
                {!!dfValue && <ToolbarButton loading={doRemoveParams.loading} confirmation={true} iconName='trash' onClick={onRemove} />}
            </div>
            {!!dfValue &&
                <TemplatesLoader
                    templateName='InlineViewContent'
                    data={{
                        schema: property.format,
                        type: props.contentType,
                        id: dfValue,
                        isCompact: false,
                    }}
                />
            }
        </WhiteCard>
    )
}
