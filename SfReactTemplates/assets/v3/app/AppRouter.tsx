import React, { useEffect } from 'react'
import { BrowserRouter as Router, Switch } from '@newageerp/v3.templates.templates-core';
import {useDidMount, useURequest} from '@newageerp/v3.bundles.hooks-bundle'
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

    const [getUserData, getUserDataParams] = useURequest(templateCore.pathMap.post['NAEauthGetProfile'])

    useEffect(() => {
        getUserData();
    }, [])
    useEffect(() => {
        if (!isMount) {
            templateCore.setUserState(getUserDataParams.data);
        }
    }, [getUserDataParams.data])

    const isRequestFinished = !!templateCore.userState && ('id' in templateCore.userState);

    const isLoggedIn = isRequestFinished && templateCore.userState.id > 0;

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
