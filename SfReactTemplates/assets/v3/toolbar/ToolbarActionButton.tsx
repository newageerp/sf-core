import React, { Fragment, useState } from 'react'
import TemplateLoader, { Template } from '../templates/TemplateLoader';
import {ToolbarButton} from '@newageerp/v3.bundles.buttons-bundle';

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
            <ToolbarButton title={props.title} iconName={props.iconName} onClick={toggleShowAfterClick}/>
            {showAfterClick && <TemplateLoader
                templates={props.afterClickContent}
                templateData={{
                    onBack: toggleShowAfterClick,
                }}
            />}
        </Fragment>
    )
}
