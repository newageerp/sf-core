import React, { useState, useEffect } from 'react'
import { MenuItemTextColor } from './OldMenuItem';
import { useNaeMenuToggle } from './OldMenuToggleProvider';

interface Props {
  icon?: string
  title: any
  children: any,
  id?: string,
}

export default function OldMenuToggle(props: Props) {
  const { lastActiveId, setLastActiveId, allowMultipleOpen } = useNaeMenuToggle();

  const [isOpen, setIsOpen] = useState(lastActiveId === props.id)

  const toggleMenu = (e: React.MouseEvent) => {
    e.preventDefault()
    setIsOpen(!isOpen)
  }

  useEffect(() => {
    if (isOpen && !!props.id) {
      setLastActiveId(props.id);
    }
  }, [isOpen, props.id]);

  useEffect(() => {
    if (!allowMultipleOpen && lastActiveId && lastActiveId !== props.id) {
      setIsOpen(false);
    }
  }, [lastActiveId, allowMultipleOpen]);

  const mainClass = isOpen ? 'bg-white bg-opacity-5 rounded-md' : ''

  return (
    <div className={'w-full ' + mainClass}>
      <a className={'flex items-center hover:bg-white hover:bg-opacity-10 px-2 py-2 rounded-md items-center'} href={'/'} onClick={toggleMenu}>
        {props.icon && <i className={MenuItemTextColor + ' opacity-70  text-sm text-center fas fa-' + props.icon + ' fa-fw mr-1'} />}{' '}
        <span className={MenuItemTextColor + "  text-sm flex-grow"}>
          {props.title}{' '}
        </span>
        {isOpen ? (
          <i className={MenuItemTextColor + ' opacity-50 fas fa-caret-up'} />
        ) : (
          <i className={MenuItemTextColor + ' opacity-50 fas fa-caret-down'} />
        )}
      </a>
      {isOpen && <div className={'px-3 py-2'}>{props.children}</div>}
    </div>
  )
}
