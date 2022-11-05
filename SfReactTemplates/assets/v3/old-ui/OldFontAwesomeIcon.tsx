import React from 'react';

export enum FontAwesomeIconType {
    Solid = "fas",
    Regular = "far",
    Light = "fal",
    Duotone = "fad"
}

export interface FontAwesomeIconProps {
    type: FontAwesomeIconType | string,
    fixedWidth?: boolean,
    icon: string,
    className?: string,
    rotate?: boolean,
}

export default function OldFontAwesomeIcon(props: FontAwesomeIconProps) {
    const className: string[] = [];

    className.push(props.type);
    className.push("fa-" + props.icon);
    if (props.fixedWidth) {
        className.push("fa-fw");
    }
    if (props.className) {
        className.push(props.className);
    }
    if (props.rotate) {
        className.push('animate-spin');
    }

    return <i className={className.join(" ")} />;
}
