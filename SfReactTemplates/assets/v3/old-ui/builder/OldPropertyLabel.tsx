import React, { Fragment } from 'react'
import { getPropertyForPath } from '../../utils';

interface Props {
    path: string,
}

export default function PropetyLabel(props: Props) {
    const { path } = props;

    const property = getPropertyForPath(path);

    if (!property) {
        return <Fragment />
    }
    return (
        <Fragment>{property.title}</Fragment>
    )
}
