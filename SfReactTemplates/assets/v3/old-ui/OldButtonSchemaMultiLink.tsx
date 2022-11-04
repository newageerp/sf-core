import React from 'react'
import { useTranslation } from 'react-i18next'

import { Menu, MenuItem } from '@szhsin/react-menu'
import { useHistory } from 'react-router-dom';
import { SFSOpenViewModalWindowProps } from '@newageerp/v3.popups.mvc-popup';

interface Props {
  className?: string
  containerClassName?: string
  schema: string
  type?: string
  id: number
  children: any
  onClick?: (schema: string, id: number) => void
  onClickDef?: string
  buttonsNl?: boolean
  customColor?: boolean
  popupId?: string
  showExtraAlways?: boolean
}

export default function OldButtonSchemaMultiLink(props: Props) {
  const { t } = useTranslation()
  const type = props.type ? props.type : 'main'
  const history = useHistory();

  const link = `/u/${props.schema}/${type}/view/${props.id}`

  const openPopup = () => {
    SFSOpenViewModalWindowProps({
      schema: props.schema,
      id: props.id,
    //   popupId: props.popupId
    })
  }

  const openNewWindow = () => {
    window.open(link, '_blank')
  }

  const openRouter = () => history.push(link)

  const onClick = () => {
    if (props.onClick) {
      props.onClick(props.schema, props.id)
    } else if (props.onClickDef === 'popup') {
      openPopup()
    } else if (props.onClickDef === 'new') {
      openPopup()
    } else {
      openRouter()
    }
  }

  const className = ['hover:underline']
  if (!props.customColor) {
    className.push('text-blue-500')
  }
  if (props.className) {
    className.push(props.className)
  }

  // const containerClassName = ['group space-x-1 flex '];
  const containerClassName = ['group']
  if (props.containerClassName) {
    containerClassName.push(props.containerClassName)
  }
  if (props.buttonsNl) {
    // containerClassName.push("justify-center flex-col");
    containerClassName.push('items-center')
  } else {
    containerClassName.push('items-center')
  }

  const buttonsLineClassName = ['space-x-1  whitespace-nowrap']
  if (!props.showExtraAlways) {
    if (props.buttonsNl) {
      // buttonsLineClassName.push("hidden group-hover:block");
      buttonsLineClassName.push('opacity-0 group-hover:opacity-100')
    } else {
      buttonsLineClassName.push('opacity-0 group-hover:opacity-100')
    }
  }

  return (
    <div
      className={
        containerClassName.join(' ') + ' flex items-center gap-1 inline-block'
      }
    >
      <button className={className.join(' ')} onClick={onClick}>
        {props.children}
      </button>

      {!!props.children &&
        <Menu
          menuButton={
            <button>
              <i className='fad fa-fw fa-chevron-down text-xs' />
            </button>
          }
        >
          <MenuItem onClick={openRouter}>
            <div className='flex items-center gap-1'>
              <i className='fad fa-window fa-fw text-nsecondary-500 hover:text-blue-500' />
              {t('Pagrindinis langas')}
            </div>
          </MenuItem>
          <MenuItem onClick={openPopup}>
            <div className='flex items-center gap-1'>
              <i className='fad fa-window-restore fa-fw text-nsecondary-500 hover:text-blue-500' />
              {t('Modalinis langas')}
            </div>
          </MenuItem>
          <MenuItem onClick={openNewWindow}>
            <div className='flex items-center gap-1'>
              <i className='fad fa-external-link-alt fa-fw text-nsecondary-500 hover:text-blue-500' />
              {t('Naujas langas')}
            </div>
          </MenuItem>
        </Menu>
      }
    </div>
  )
}
