import React from 'react'
import { Template, TemplatesParser, useTemplatesLoader } from '@newageerp/v3.templates.templates-core'
import { NaePopupProvider } from '../old-ui/OldPopupProvider';
import { Popup } from '@newageerp/v3.bundles.popup-bundle'

interface Props {
  children: Template[],
  className?: string,
  title?: string,
}

export default function PopupWindow(props: Props) {
  const { data: tdata } = useTemplatesLoader();

  const onClosePopup = tdata.onWindowClose?tdata.onWindowClose:tdata.onBack;

  return (
    <NaePopupProvider isPopup={true} onClose={onClosePopup}>
      <Popup className={props.className} isPopup={true} onClick={onClosePopup} title={props.title ? props.title : ''}>
        <TemplatesParser templates={props.children} />
      </Popup>
    </NaePopupProvider>
  )
}
