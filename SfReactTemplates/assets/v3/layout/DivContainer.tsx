import { parseChildrenNode, Template } from '@newageerp/v3.templates.templates-core';
import classNames from 'classnames';
import React, { ReactNode } from 'react'

type Props = {
    children?: ReactNode | Template[];
    className?: string,
}

export default function DivContainer(props: Props) {
  return (
    <div className={classNames(props.className)}>
        {parseChildrenNode(props.children)}
    </div>
  )
}
