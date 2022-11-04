import React, { Fragment } from 'react'
import { useBuilderWidget } from './OldBuilderWidgetProvider';

interface Props {
  hideScopes?: string,
  showScopes?: string,
  children: any,
}

export default function UIBShowHideScopes(props: Props) {
  const hideScopes = props.hideScopes ? JSON.parse(props.hideScopes) : [];
  const showScopes = props.showScopes ? JSON.parse(props.showScopes) : [];

  const parentElement = useBuilderWidget().element;

  const elementScopes = parentElement && parentElement.scopes ? parentElement.scopes : [];

  let isShow = true;

  if (showScopes.length > 0) {
    const intersections = elementScopes.filter(function (n: string) {
      return showScopes.indexOf(n) !== -1
    })
    isShow = intersections.length > 0
    // console.log({ intersections });
  }
  if (hideScopes.length > 0) {
    const intersections = elementScopes.filter(function (n: string) {
      return hideScopes.indexOf(n) !== -1
    })

    if (intersections.length > 0) {
      isShow = false
    }
  }
  if (!isShow) {
    return <Fragment />
  }

  return (
    <Fragment>{props.children}</Fragment>
  )
}
