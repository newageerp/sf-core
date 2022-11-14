import React from 'react'
import { Template, TemplatesParser, useTemplateLoader } from '../templates/TemplateLoader'
import { NaePopupProvider } from '../old-ui/OldPopupProvider';
import { Popup } from '@newageerp/v3.popups.popup'
interface Props {
  children: Template[],
  // size?: PopupSize,
  title?: string,
}

export default function PopupWindow(props: Props) {
  const { data: tdata } = useTemplateLoader();

  return (
    <NaePopupProvider isPopup={true} onClose={tdata.onBack}>
      <Popup isPopup={true} onClick={tdata.onBack} title={props.title ? props.title : ''}>
        <TemplatesParser templates={props.children} />
      </Popup>
    </NaePopupProvider>
  )
}
