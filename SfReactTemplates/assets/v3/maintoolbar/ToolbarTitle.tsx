import React, { Fragment, useEffect } from 'react'
import { useRecoilState } from 'recoil';
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
