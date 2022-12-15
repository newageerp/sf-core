import React, { Fragment, useEffect } from 'react'
import { useRecoilState } from '@newageerp/v3.templates.templates-core';
import { UserSpaceWrapperToolbarState } from '../app/UserSpace/UserSpaceWrapperToolbar';

interface Props {
    title: string;
}

export default function ToolbarTitle(props: Props) {
    const [, setToolbarTitle] = useRecoilState(UserSpaceWrapperToolbarState)

    useEffect(() => {
        console.log('setToolbarTitle', props.title);
        setToolbarTitle(props.title);
    }, [props.title]);

    return (
        <Fragment />
    )
}
