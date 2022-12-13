import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import React, { Fragment, useEffect, useState } from 'react'
import { Input } from "@newageerp/ui.form.base.form-pack";

interface Props {
  action: string,

  value: string,
  updateValue: (val: string) => void,
}

interface IElement {
  title: string
  text: string
}

export default function FormFieldTagCloud(props: Props) {
  const [search, setSearch] = useState('')
  const [getData, getDataParams] = OpenApi.useURequest<IElement>(props.action)
  useEffect(() => {
    getData();
  }, [])

  const val = props.value;

  const onClick = (s: string) => {
    if (!settings.actionType || settings.actionType === 'append') {
      let newVal = val
      if (newVal) {
        newVal += '\n'
      }
      newVal += s
      props.updateValue(newVal);
    } else {
      props.updateValue(s);
    }
  }

  // @ts-ignore
  const settings = getDataParams.data.settings ? getDataParams.data.settings : {}

  let isShowActions = true
  if (settings.showOnlyOnSearch) {
    isShowActions = !!search
  }

  const data: IElement[] = getDataParams.data.data

  return (
    <div className='flex flex-wrap gap-2'>
      {settings.showSearch && (
        <Input
          placeholder='paieška...'
          value={search}
          onChange={(e) => setSearch(e.target.value)}
        />
      )}
      {isShowActions && (
        <Fragment>
          {data
            .filter((s) => {
              if (!search) {
                return true
              }
              return (
                s.title.toLowerCase().indexOf(search) >= 0 ||
                s.text.toLowerCase().indexOf(search) >= 0
              )
            })
            .map((s: IElement, sIndex: number) => {
              return (
                <button
                  key={'b-' + sIndex}
                  onClick={() => onClick(s.text)}
                  title={s.text}
                  className={'tw3-text-xs tw3-text-left hover:tw3-underline tw3-text-blue-800'}
                >
                  {s.title}
                </button>
              )
            })}
        </Fragment>
      )}
    </div>
  )
}
