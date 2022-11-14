import React from 'react'
import { Template, TemplatesParser, useTemplateLoader } from '../templates/TemplateLoader'
import { Popup } from '@newageerp/ui.popups.base.popup'
import { NaePopupProvider } from '../old-ui/OldPopupProvider';

interface Props {
  children: Template[],
  size?: string,
}

export default function PopupWindow(props: Props) {
  const { data: tdata } = useTemplateLoader();

  return (
    <NaePopupProvider isPopup={true} onClose={tdata.onBack}>
      <Popup onClose={tdata.onBack} size={props.size}>
        <TemplatesParser templates={props.children} />
      </Popup>
    </NaePopupProvider>
  )
}
