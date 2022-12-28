import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import React, { Fragment, useEffect, useState } from 'react'
import { Input } from "@newageerp/ui.form.base.form-pack";
import { useTranslation } from 'react-i18next';
import classNames from 'classnames';

interface Props {
  action: string,
  element?: any,

  value: any,
  updateValue: (val: any) => void,
}

interface IElement {
  title: string
  text: string
}

export default function FormFieldTagCloud(props: Props) {
  const { t } = useTranslation();
  const [search, setSearch] = useState('')
  const [getData, getDataParams] = OpenApi.useURequest<IElement>(props.action)
  useEffect(() => {
    getData({
      element: props.element
    });
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
    } else if (settings.actionType === 'append-array') {
      val.push(s);
      props.updateValue(val);
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

  const data: IElement[] = getDataParams.data.data;

  const styles : any = {
    'xs': 'tw3-text-xs',
    'sm': 'tw3-text-sm',
    'base': 'tw3-text-base',
  }
  const style = settings.style ? settings.style : 'xs';

  return (
    <div className='tw3-flex tw3-flex-wrap tw3-gap-2'>
      {settings.showSearch && (
        <Input
          placeholder={`${t('search')}...`}
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
                  className={classNames('tw3-text-left hover:tw3-underline tw3-text-blue-800', styles[style])}
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
