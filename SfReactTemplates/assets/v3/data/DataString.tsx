import React, { Fragment } from 'react'

interface Props {
    contents: string,
}

export default function DataString(props: Props) {
  return (
    <Fragment>{props.contents}</Fragment>
  )
}
