import React, { Fragment, useState } from 'react'
import { TemplatesLoader, Template } from '@newageerp/v3.templates.templates-core';
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle';

interface Props {
    title: string,
    iconName: string,
    afterClickContent: Template[],
}

export default function ToolbarActionButton(props: Props) {
    const [showAfterClick, setShowAfterClick] = useState(false);
    const toggleShowAfterClick = () => setShowAfterClick(!showAfterClick);
    return (
        <Fragment>
            <ToolbarButton title={props.title} iconName={props.iconName} onClick={toggleShowAfterClick} />
            {showAfterClick && <TemplatesLoader
                templates={props.afterClickContent}
                templateData={{
                    onBack: toggleShowAfterClick,
                }}
            />}
        </Fragment>
    )
}
