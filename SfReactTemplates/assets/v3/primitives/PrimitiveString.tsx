import React, {Fragment} from 'react'

type Props = {
    text: string,
}

export default function PrimitiveString(props: Props) {
  return (
    <Fragment>{props.text}</Fragment>
  )
}
