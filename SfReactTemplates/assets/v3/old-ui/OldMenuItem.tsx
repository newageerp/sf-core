import React, { useState } from 'react'
import { useHistory } from 'react-router-dom';

interface Props {
  title: string
  path: string
  icon?: string
  options?: any,
  contentAfterTitle?: any,
}

export const MenuItemTextColor = 'text-nsecondary-100';

export default function OldMenuItem(props: Props) {
  const history = useHistory();
  
  const [effect, setEffect] = useState(false);

  const { title, path, icon, options } = props;

  const goTo = (e: any) => {
    e.preventDefault();
    setEffect(true);

    history.push(path, options);
  }

  return (
    <a className={`flex items-center hover:bg-white hover:bg-opacity-10 px-2 py-2 rounded-md items-center ${effect ? "animate-menupulse" : ""}`} href={path} onClick={goTo} title={title} onAnimationEnd={() => setEffect(false)}>
      {!!icon && <i className={MenuItemTextColor + ' opacity-70 text-center text-sm fas fa-' + icon + ' fa-fw mr-1'} />}
      <span className={MenuItemTextColor + " text-sm flex-grow"}>{title}</span>
      {!!props.contentAfterTitle && props.contentAfterTitle}
    </a>
  )
}
