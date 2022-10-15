import React from 'react'
import { Template, TemplatesParser, useTemplateLoader } from '../templates/TemplateLoader'
import { Popup } from '@newageerp/ui.popups.base.popup'
import { UI } from '@newageerp/nae-react-ui';

interface Props {
  children: Template[]
}

export default function PopupWindow(props: Props) {
  const { data: tdata } = useTemplateLoader();

  return (
    <UI.Popup.NaePopupProvider isPopup={true} onClose={tdata.onBack}>
      <Popup onClose={tdata.onBack}>
        <TemplatesParser templates={props.children} />
      </Popup>
    </UI.Popup.NaePopupProvider>
  )
}
