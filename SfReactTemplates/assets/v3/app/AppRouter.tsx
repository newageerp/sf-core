import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import React, { useEffect, useState } from 'react'
import { BrowserRouter as Router, Switch } from 'react-router-dom';
import axios from 'axios'
import { useRecoilState } from '@newageerp/v3.templates.templates-core';
import {useDidMount} from '@newageerp/v3.bundles.hooks-bundle'
import { useTemplatesCore } from '@newageerp/v3.templates.templates-core';

export interface AppRouterRoute {
    path: string
    comp: any,
    exact?: boolean
}

export interface AppRouterProps {
    noAuthRoutes?: (isLoggedIn: boolean) => any,
    authRoutes?: any,
    children?: any
}

export default function AppRouter(props: AppRouterProps) {
    const templateCore = useTemplatesCore();
    const isMount = useDidMount();
    const [userState, setUserState] = useRecoilState<any>(OpenApi.naeUserState)

    const [getUserData, getUserDataParams] = OpenApi.useURequest('NAEauthGetProfile')

    useEffect(() => {
        getUserData();
    }, [])
    useEffect(() => {
        if (!isMount) {
            templateCore.setUserState(getUserDataParams.data);
            setUserState(getUserDataParams.data)
        }
    }, [getUserDataParams.data])

    const isRequestFinished = !!userState && ('id' in userState);

    const isLoggedIn = isRequestFinished && userState.id > 0;

    if (!isRequestFinished) {
        return <div>Loading...</div>
    }

    return (
        <Router>
            <Switch>
                {!!props.noAuthRoutes && props.noAuthRoutes(isLoggedIn)}
            </Switch>
            {isLoggedIn && props.authRoutes}
            {props.children}
        </Router>
    )
}
