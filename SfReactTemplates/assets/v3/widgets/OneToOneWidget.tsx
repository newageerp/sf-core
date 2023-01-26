import React, { Fragment, useState } from 'react'
import { useTranslation } from 'react-i18next';
import { WhiteCard } from '@newageerp/v3.bundles.widgets-bundle'
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle';
import { TextCardTitle } from '@newageerp/v3.bundles.typography-bundle';
import { TemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { filterScopes, getPropertyForPath } from '../utils';
import { useDfValue } from '../hooks/useDfValue';
import { Tooltip } from '@newageerp/v3.bundles.badges-bundle';
import { useTemplatesCore } from '@newageerp/v3.templates.templates-core';

type Props = {
    path: string,
    id: number,
    contentType: string,

    showOnExist: boolean,

    showScopes: any,
    createScopes: any,
    editScopes: any,
    removeScopes: any,
}

export default function OneToOneWidget(props: Props) {
    const {userState} = useTemplatesCore()

    const property = getPropertyForPath(props.path);
    const scopesPath = `${props.path}.scopes`;
    const scopesProperty = getPropertyForPath(scopesPath);

    const dfValue = useDfValue({ path: `${props.path}.id`, id: props.id });
    const dfScopesValue = useDfValue({ path: scopesPath, id: props.id });
    
    const elementScopes = dfScopesValue && !!scopesProperty ? dfScopesValue : [];

    const { t } = useTranslation();

    const relClass = property && property.format ? property.format : '-';

    const [doRemove, doRemoveParams] = OpenApi.useURemove(relClass)

    const isShowScope = filterScopes(
        { scopes: elementScopes },
        userState,
        props.showScopes,
    );
    const isCreateScope = filterScopes(
        { scopes: elementScopes },
        userState,
        props.createScopes,
    );
    const isEditScope = filterScopes(
        { scopes: elementScopes },
        userState,
        props.editScopes,
    );
    const isRemoveScope = filterScopes(
        { scopes: elementScopes },
        userState,
        props.removeScopes,
    );

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
    if (!isShowScope) {
        return <Fragment />
    }
    if (!dfValue && props.showOnExist) {
        return <Fragment />
    }

    return (
        <WhiteCard isCompact={true}>
            <div className='tw3-flex tw3-items-center tw3-gap-2'>
                <TextCardTitle className='tw3-flex-grow'>{t(property.title)}</TextCardTitle>
                {!!property.description && <Tooltip text={property.description} />}
                {!dfValue && isCreateScope && <ToolbarButton iconName='plus' onClick={onCreate} />}
                {!!dfValue && isEditScope && <ToolbarButton iconName='edit' onClick={onEdit} />}
                {!!dfValue && isRemoveScope && <ToolbarButton loading={doRemoveParams.loading} confirmation={true} iconName='trash' onClick={onRemove} />}
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
